<?php

namespace KMA\Modules\KMAForms;

use \WP_REST_Request;
use KMA\Modules\KMAMail\KMAMail;
use KMA\Modules\KMAMail\Message;

class Booking extends ContactForm {

    public $bookingUrl;
    public $property;
    public $dates;
    public $reservationnumber;
    public $adults;
    public $children;
    public $pets;
    public $travelinsurance;
    public $heatedpool;
    public $totalamount;

    public function use()
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function submitBookingForm(WP_REST_Request $request)
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

    /**
     * Add REST API routes
     */
    public function registerRoutes()
    {
        register_rest_route(
            'kerigansolutions/v1',
            '/submit-booking',
            [
                'methods' => 'POST',
                'callback' => [$this, 'submitBookingForm']
            ]
        );
    }

    public function sendEmail()
    {
        $escOptions = get_option('kma-escapia');

        $headers  = 'MIME-Version: 1.0' . PHP_EOL;
        $headers .= 'Content-type: text/html; charset=utf-8' . PHP_EOL;

        $message = new Message();
        $message->setHeadline('New Online Booking: ' . $this->property)
                ->setBody($this->messageBody('You\'ve received a new property inquiry.'))
                ->setHeaders($headers)
                ->setSubject('New Online Booking: ' . $this->property)
                ->setPrimaryColor('#AE9A45')
                ->setSecondaryColor('#325265')
                ->to($escOptions['booking-email']);
                // ->to('web@kerigan.com');

        $mail = new KMAMail($message);
        $mail->send();
    }

    public function messageBody( $introText )
    {
        return '
        <p>'.$introText.'</p>' .
        $this->formInformation() . 
        '<p><a style="font-weight: bold;" href="'.$this->bookingUrl.'" target="_blank" >View Booking</a></p>';
    }

    public function sendBounceback()
    {
        $headers  = 'MIME-Version: 1.0' . PHP_EOL;
        $headers .= 'Content-type: text/html; charset=utf-8' . PHP_EOL;

        $message = new Message();
        $message->setHeadline('Your stay at ' . $this->property)
                ->setBody($this->bounceMessageBody('We\'re excited to meet you! While we put together the remaining details, please review what you submitted below. We\'ll be in touch soon!' ))
                ->setHeaders($headers)
                ->setSubject('Your stay at ' . $this->property)
                ->setPrimaryColor('#AE9A45')
                ->setSecondaryColor('#325265')
                ->to($this->email);

        $mail = new KMAMail($message);
        $mail->send();
    }

    public function bounceMessageBody( $introText )
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
            <tr><td>Property Name</td><td>' . $this->property . '</td></tr>
            <tr><td>Date Range</td><td>' . $this->dates . '</td></tr>
            <tr><td>Reservation Number</td><td>' . $this->reservationnumber . '</td></tr>
            <tr><td>Adults</td><td>' . $this->adults . '</td></tr>
            <tr><td>Children</td><td>' . $this->children . '</td></tr>
            <tr><td>Pets</td><td>' . $this->pets . '</td></tr>
            <tr><td>Travel Insurance</td><td>' . $this->travelinsurance . '</td></tr>
            <tr><td>Heated Pool</td><td>' . $this->heatedpool . '</td></tr>
            <tr><td>Total Amount</td><td>' . $this->totalamount . '</td></tr>
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
        $bookingUrl = $this->request->get_param('bookingurl') !== '' ? $this->request->get_param('bookingurl') : null;
        $reservationnumber = $this->request->get_param('reservationnumber') !== '' ? $this->request->get_param('reservationnumber') : null;
        $adults = $this->request->get_param('adults') !== '' ? $this->request->get_param('adults') : null;
        $children = $this->request->get_param('children') !== '' ? $this->request->get_param('children') : null;
        $pets = $this->request->get_param('pets') !== '' ? $this->request->get_param('pets') : null;
        $travelinsurance = $this->request->get_param('travelinsurance') !== '' ? $this->request->get_param('travelinsurance') : null;
        $heatedpool = $this->request->get_param('heatedpool') !== '' ? $this->request->get_param('heatedpool') : null;
        $totalamount = $this->request->get_param('totalamount') !== '' ? $this->request->get_param('totalamount') : null;

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

        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->comments = $comments;
        $this->dates = $dates;
        $this->property = $property;
        $this->bookingUrl = $bookingUrl;
        $this->property = $property;
        $this->dates = $dates;
        $this->reservationnumber = $reservationnumber;
        $this->adults = $adults;
        $this->children = $children;
        $this->pets = $pets;
        $this->travelinsurance = $travelinsurance;
        $this->heatedpool = $heatedpool;
        $this->totalamount = $totalamount;

        return false;
    }
}