<?php
namespace KMA\Modules\KMAMail;

class Message
{
    public $to;
    public $body;
    public $headers;
    public $subject;
    public $headline;
    public $primaryColor;
    public $secondaryColor;

    public function __construct()
    {
        $this->primaryColor = '#AE9A45';
        $this->secondaryColor = '#40A796';
    }

    public function setPrimaryColor(string $hex)
    {
        $this->primaryColor = $hex;

        return $this;
    }

    public function setSecondaryColor(string $hex)
    {
        $this->secondaryColor = $hex;

        return $this;
    }

    public function to(string $to = 'bryan@kerigan.com')
    {
        $this->to = $to;

        return $this;
    }

    public function setSubject(string $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function setHeaders(string $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    public function setBody(string $body)
    {
        $this->body = $body;

        return $this;
    }

    public function setHeadline(string $headline)
    {
        $this->headline = $headline;

        return $this;
    }
}
