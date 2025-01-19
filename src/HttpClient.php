<?php

namespace SmartPay;

use Exception;

class HttpClient
{
    public function post($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('HTTP Request Error: ' . curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }
}
