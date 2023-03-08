<?php
namespace App\Service;

use Twilio\Rest\Client;

class SmsService
{
    private $accountSid;
    private $authToken;
    private $fromNumber;
    private $toNumber;

    public function __construct(string $accountSid, string $authToken, string $fromNumber, string $toNumber)
    {
        $this->accountSid = $accountSid;
        $this->authToken = $authToken;
        $this->fromNumber = $fromNumber;
        $this->toNumber = $toNumber;
    }


    public function sendSms($message)
    {
        $client = new Client($this->accountSid, $this->authToken);

        $client->messages->create(
            $this->toNumber,
            array(
                'from' => $this->fromNumber,
                'body' => $message
            )
        );
    }
}