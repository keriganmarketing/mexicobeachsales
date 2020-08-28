<?php

namespace KMA\Modules\KMAForms;

use \WP_REST_Request;
use KMA\Modules\KMAMail\KMAMail;
use KMA\Modules\KMAMail\Message;
use ReCaptcha\ReCaptcha;

class ContactForm
{
    public $name;
    public $email;
    public $request;
    public $success;
    public $comments;
    public $errorCode;
    public $errorMessage;

    const VALIDATION_ERROR = ['status' => 422];

    public function use()
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
        add_shortcode( 'contact-form', [$this, 'makeShortcode'] );
    }

    public function submitContactForm(WP_REST_Request $request)
    {
        $this->request = $request;

        if ($this->hasErrors()) {
            return new \WP_Error($this->errorCode, $this->errorMessage, self::VALIDATION_ERROR);
        }
        $this->persistToDashboard();
        $this->sendEmail();
        $this->sendBounceback();

        return rest_ensure_response(json_encode(['message' => 'Success']));
    }

    public function sendEmail()
    {
        $headers  = 'MIME-Version: 1.0' . PHP_EOL;
        $headers .= 'Content-type: text/html; charset=utf-8' . PHP_EOL;

        $message = new Message();
        $message->setHeadline('New Contact Request From Website')
                ->setBody($this->messageBody('You\'ve received a new contact request.'))
                ->setHeaders($headers)
                ->setSubject('New Lead From Website')
                ->setPrimaryColor('#111111')
                ->setSecondaryColor('#555555')
                ->to(get_field('email', 'option'));
                // ->to('web@kerigan.com');

        $mail = new KMAMail($message);
        $mail->send();
    }

    public function sendBounceback()
    {
        $headers  = 'MIME-Version: 1.0' . PHP_EOL;
        $headers .= 'Content-type: text/html; charset=utf-8' . PHP_EOL;

        $message = new Message();
        $message->setHeadline('Thank you for contacting us')
                ->setBody($this->messageBody('Here\'s a copy of what you submitted. We\'ll be in touch soon!' ))
                ->setHeaders($headers)
                ->setSubject('Your Contact Request')
                ->setPrimaryColor('#111111')
                ->setSecondaryColor('#555555')
                ->to($this->email);

        $mail = new KMAMail($message);
        $mail->send();
    }

    public function messageBody( $introText )
    {
        return '
        <p>'.$introText.'</p>' .
        $this->formInformation();
    }

    public function formInformation()
    {
        return '
        <table cellspacing="0" cellpadding="0" border="0" class="datatable">
            <tr><td>Name</td><td>' . $this->name . '</td></tr>
            <tr><td>Email</td><td>' . $this->email . '</td></tr>
            <tr><td>Phone Number</td><td>' . $this->phone . '</td></tr>
            <tr><td>Message</td><td>' . $this->comments  . '</td></tr>
        </table>
        ';
    }

    /**
     * Add REST API routes
     */
    public function registerRoutes()
    {
        register_rest_route(
            'kerigansolutions/v1',
            '/submit-contact-form',
            [
                'methods' => 'POST',
                'callback' => [$this, 'submitContactForm'],
                'permission_callback' => '__return_true'
            ]
        );
    }

    public function persistToDashboard()
    {
        $defaults = [
            'post_title'  => $this->name,
            'post_type'   => 'contact_request',
            'menu_order'  => 0,
            'post_status' => 'publish'
        ];

        $id = wp_insert_post($defaults);
        foreach ($this->request->get_params() as $key => $value) {
            if ($key !== 'name') {
                update_post_meta($id, $key, $value);
            }
        }
    }

    public function hasErrors()
    {
        $name =  $this->request->get_param('name') !== '' ? $this->request->get_param('name') : null;
        $email = $this->request->get_param('email') !== '' ? $this->request->get_param('email') : null;
        $phone = $this->request->get_param('phone') !== '' ? $this->request->get_param('phone') : null;
        $comments = $this->request->get_param('comments') !== '' ? $this->request->get_param('comments') : null;

        if(! $this->validate($this->request->get_param('token'))){
            $this->errorCode = 'recptcha_failed';
            $this->errorMessage = 'ReCaptcha validation failed';

            return true;
        }

        if ($name === null) {
            $this->errorCode = 'name_required';
            $this->errorMessage = 'The name field is required';

            return true;
        }
        if ($email === null) {
            $this->errorCode = 'email_required';
            $this->errorMessage = 'The email field is required';

            return true;
        }
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errorCode = 'invalid_email';
            $this->errorMessage = 'The email address you entered is invalid';

            return true;
        }

        if ($comments === null) {
            $this->errorCode = 'comments_required';
            $this->errorMessage = 'The message field is required';

            return true;
        }

        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->comments = $comments;

        return false;
    }

    public function makeShortcode($atts)
    {
        $a = shortcode_atts([], 'contact-form', $atts);

        ob_start(); ?>
        <contact-form class="contact-form" sitekey="<?php echo env('GOOGLE_RECAPTCHA_SITEKEY', 'ABCD'); ?>" ></contact-form>
        <?php return ob_get_clean();
    }

    public function validate($recaptchaResponse)
    {
        $valid = false;

        if($recaptchaResponse === ''){
            return $valid;
        }
    
        $recaptcha = new ReCaptcha(env('GOOGLE_RECAPTCHA_SECRETKEY', 'ABCD'));

        $resp = $recaptcha->setExpectedHostname(env('DOMAIN', 'www.test.com'))
                    ->setScoreThreshold(env('SCORE_THRESHOLD', 0.5))
                    ->verify($recaptchaResponse, $this->getIP());

        // echo '<pre>',print_r($resp),'</pre>';
        // echo 'IP: ' . $this->getIP();

        if ($resp->isSuccess()) {
            $valid = true;
        }

        return $valid;    
    }

    public function getIP()
    {
        $Ip = '0.0.0.0';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '')
            $Ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '')
            $Ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '')
            $Ip = $_SERVER['REMOTE_ADDR'];
        if (($CommaPos = strpos($Ip, ',')) > 0)
            $Ip = substr($Ip, 0, ($CommaPos - 1));
    
        return $Ip;
    }
}
