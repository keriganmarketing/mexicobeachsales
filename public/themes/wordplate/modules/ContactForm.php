<?php

namespace Includes\Modules;

use \WP_REST_Request;
use Includes\Modules\KMAMail\KMAMail;
use Includes\Modules\KMAMail\Message;

class ContactForm
{
    public $name;
    public $email;
    public $request;
    public $success;
    public $comments;
    public $errorCode;
    public $errorMessage;
    public $listing;
    public $address;
    public $image;
    public $price;

    const VALIDATION_ERROR = ['status' => 422];

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
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
        $message->setHeadline('New Lead From Website')
                ->setBody($this->messageBody('You\'ve received a new lead.'))
                ->setHeaders($headers)
                ->setSubject('New Lead From Website')
                ->setPrimaryColor('#de0a29')
                ->setSecondaryColor('#59332a')
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
        $message->setHeadline('Thank you for contacting me')
                ->setBody($this->messageBody('Here\'s a copy of what you submitted. I\'ll be in touch soon!' ))
                ->setHeaders($headers)
                ->setSubject('Your Contact Request')
                ->setPrimaryColor('#de0a29')
                ->setSecondaryColor('#59332a')
                ->to($this->email);

        $mail = new KMAMail($message);
        $mail->send();
    }

    public function messageBody( $introText )
    {
        return '
        <p>'.$introText.'</p>' .
        $this->formInformation() . 
        $this->listingInfo();
    }

    public function formInformation()
    {
        return '
        <table cellspacing="0" cellpadding="0" border="0" class="datatable">
            <tr><td>Name</td><td>' . $this->name . '</td></tr>
            <tr><td>Email</td><td>' . $this->email . '</td></tr>
            <tr><td>Phone Number</td><td>' . $this->phone . '</td></tr>
            <tr><td>Additional Information</td><td>' . $this->comments  . '</td></tr>
        </table>
        ';
    }

    public function listingInfo()
    {        
        if($this->listing == ''){
            return '';
        }

        return '<br>        
        <table cellspacing="0" cellpadding="0" border="0" class="datatable">
            <tr><td colspan="2">Property Info</td></tr>
            <tr>
                <td width="50%"><img src="' . $this->image . '" width="100%" ></td>
                <td width="50%">
                    <p>' . $this->address . '</p>
                    <p>$' . number_format($this->price) . '</p>
                    <p>MLS# ' . $this->listing . '</p>
                    <p><a href="'.get_home_url().'/listing/'.$this->listing.'" >view on website</a></p>
                </td>
            </tr>
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
        $listing = $this->request->get_param('listing') !== '' ? $this->request->get_param('listing') : null;
        $address = $this->request->get_param('address') !== '' ? $this->request->get_param('address') : null;
        $price = $this->request->get_param('price') !== '' ? $this->request->get_param('price') : null;
        $image = $this->request->get_param('image') !== '' ? $this->request->get_param('image') : null;

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

        // if ($comments === null) {
        //     $this->errorCode = 'comments_required';
        //     $this->errorMessage = 'The message field is required';

        //     return true;
        // }

        // if ($phone === null) {
        //     $this->errorCode = 'phone_required';
        //     $this->errorMessage = 'The phone field is required';

        //     return true;
        // }

        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->listing = $listing;
        $this->comments = $comments;
        $this->price = $price;
        $this->image = $image;
        $this->address = $address;

        return false;
    }
}
