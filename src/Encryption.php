<?php

namespace SmartPay;

class Encryption
{
    public static function encrypt($data, $key)
    {
        $iv = random_bytes(16);
        $ciphertext = openssl_encrypt($data, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
        return bin2hex($iv . $ciphertext . $tag);
    }

    public static function decrypt($encryptedData, $key)
    {
        $data = hex2bin($encryptedData);
        $iv = substr($data, 0, 16);
        $tag = substr($data, -16);
        $ciphertext = substr($data, 16, -16);

        return openssl_decrypt($ciphertext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
    }
}
