<?php

namespace SmartPay;

class Config
{
    private mixed $merchantId;
    private mixed $accessCode;
    private mixed $workingKey;
    private mixed $redirectUrl;
    private mixed $cancelUrl;

    public function __construct($settings = [])
    {
        $this->merchantId = $settings['merchant_id'] ?? '';
        $this->accessCode = $settings['access_code'] ?? '';
        $this->workingKey = $settings['working_key'] ?? '';
        $this->redirectUrl = $settings['redirect_url'] ?? '';
        $this->cancelUrl = $settings['cancel_url'] ?? '';
    }

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function getAccessCode()
    {
        return $this->accessCode;
    }

    public function getWorkingKey()
    {
        return $this->workingKey;
    }

    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    public function getCancelUrl()
    {
        return $this->cancelUrl;
    }
}
