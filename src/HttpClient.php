<?php

namespace SmartPay;

use Exception;
use Illuminate\Support\Facades\Log;
class HttpClient
{
    public function send($url, $data)
    {
        try {
            $response = \Illuminate\Support\Facades\Http::post($url, $data);

            if ($response->failed()) {
                throw new Exception('HTTP Request failed with status: ' . $response->status());
            }
            return $response->body();
        } catch (Exception $e) {
            Log::error('HTTP Request Error: ' . $e->getMessage());
            throw $e;
        }
    }
    public function post($url, $data)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            throw new Exception('HTTP Request Error: ' . $error_msg);
        }

        $responseInfo = curl_getinfo($ch);

        if ($responseInfo['http_code'] != 200) {
            curl_close($ch);
            throw new Exception('HTTP Request failed with status: ' . $responseInfo['http_code']);
        }

        curl_close($ch);

        return $response;
    }
}