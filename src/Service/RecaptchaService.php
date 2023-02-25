<?php

namespace App\Service;

use ReCaptcha\ReCaptcha;

class RecaptchaService
{
    private $recaptcha;
    private $secretKey;

    public function __construct($secretKey)
    {
        $this->recaptcha = new ReCaptcha($secretKey);
        $this->secretKey = $secretKey;
    }

    public function validate($response)
    {
        $resp = $this->recaptcha->verify($response, $_SERVER['REMOTE_ADDR']);

        if (!$resp->isSuccess()) {
            return false;
        }

        return true;
    }
}
