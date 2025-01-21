<?php

namespace SmartPay;

use SmartPay\Encryption;
use SmartPay\HttpClient;

class SmartPay
{
    private $merchantId;
    private $accessCode;
    private $workingKey;

    public function __construct($merchantId, $accessCode, $workingKey)
    {
        $this->merchantId = $merchantId;
        $this->accessCode = $accessCode;
        $this->workingKey = $workingKey;
    }

    public function initiatePayment($orderId, $amount, $currency = 'OMR', $redirectUrl, $cancelUrl)
    {
        $requestData = [
            'merchant_id' => $this->merchantId,
            'order_id' => $orderId,
            'currency' => $currency,
            'amount' => $amount,
            'redirect_url' => $redirectUrl,
            'cancel_url' => $cancelUrl,
        ];

        $encryptedRequest = Encryption::encrypt(http_build_query($requestData), $this->workingKey);

        $httpClient = new HttpClient();
        return $httpClient->post('https://mti.bankmuscat.com:6443/transaction.do?command=initiateTransaction', [
            'access_code' => $this->accessCode,
            'encRequest' => $encryptedRequest,
        ]);
    }

    public function processResponse($encResponse)
    {
        return Encryption::decrypt($encResponse, $this->workingKey);
    }
}
