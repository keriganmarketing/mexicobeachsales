<?php

namespace KMA\Modules\KMAForms;

use \WP_REST_Request;
use KMA\Modules\KMAMail\KMAMail;
use KMA\Modules\KMAMail\Message;

class Inquiry extends ContactForm {

    public $dates;
    public $property;
    
    public function submitContactForm(WP_REST_Request $request)
    {
        $this->request = $request;

        if ($this->hasErrors()) {
            return new \WP_Error($this->errorCode, $this->errorMessage, self::VALIDATION_ERROR);
        }
        // $this->persistToDashboard();
        $this->sendEmail();
        $this->sendBounceback();

        return rest_ensure_response(json_encode(['message' => 'Success']));
    }

    public function sendEmail()
    {
        $escOptions = get_option('kma-escapia');

        $headers  = 'MIME-Version: 1.0' . PHP_EOL;
        $headers .= 'Content-type: text/html; charset=utf-8' . PHP_EOL;
        $headers .= 'CC: ' . $escOptions['booking-email'] . PHP_EOL;

        $message = new Message();
        $message->setHeadline('New Property Inquiry Regarding ' . $this->property)
                ->setBody($this->messageBody('You\'ve received a new property inquiry.'))
                ->setHeaders($headers)
                ->setSubject('New Property Inquiry: ' . $this->property)
                ->setPrimaryColor('#AE9A45')
                ->setSecondaryColor('#325265')
                ->to($escOptions['inquiry-email']);
                // ->to('web@kerigan.com');

        $mail = new KMAMail($message);
        $mail->send();
    }

    public function sendBounceback()
    {
        $headers  = 'MIME-Version: 1.0' . PHP_EOL;
        $headers .= 'Content-type: text/html; charset=utf-8' . PHP_EOL;

        $message = new Message();
        $message->setHeadline('Thank you for inquiring about ' . $this->property)
                ->setBody($this->messageBody('Here\'s a copy of what you submitted. We\'ll be in touch soon!' ))
                ->setHeaders($headers)
                ->setSubject('Your Property Inquiry Regarding ' . $this->property)
                ->setPrimaryColor('#AE9A45')
                ->setSecondaryColor('#325265')
                ->to($this->email);

        $mail = new KMAMail($message);
        $mail->send();
    }

    public function formInformation()
    {
        return '
        <table cellspacing="0" cellpadding="0" border="0" class="datatable">
            <tr><td>Name</td><td>' . $this->name . '</td></tr>
            <tr><td>Email</td><td>' . $this->email . '</td></tr>
            <tr><td>Phone Number</td><td>' . $this->phone . '</td></tr>
            <tr><td>Property Name</td><td>' . $this->property . '</td></tr>
            <tr><td>Date Range</td><td>' . $this->dates . '</td></tr>
            <tr><td>Additional Information</td><td>' . $this->comments  . '</td></tr>
        </table>
        ';
    }

    public function hasErrors()
    {
        $name =  $this->request->get_param('name') !== '' ? $this->request->get_param('name') : null;
        $email = $this->request->get_param('email') !== '' ? $this->request->get_param('email') : null;
        $phone = $this->request->get_param('phone') !== '' ? $this->request->get_param('phone') : null;
        $comments = $this->request->get_param('comments') !== '' ? $this->request->get_param('comments') : null;
        $dates = $this->request->get_param('dates') !== '' ? $this->request->get_param('dates') : null;
        $property = $this->request->get_param('property') !== '' ? $this->request->get_param('property') : null;

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
        $this->dates = $dates;
        $this->property = $property;


        return false;
    }
}