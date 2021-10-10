<?php

namespace App\Infrastructure\Utility;

class MyCrypt
{
    private const ENCRYPT_METHOD = 'aes-256-cbc';

    public static function encrypt(string $cryptKey, string $value, $dailyQr = false)
    {
        $iv = $dailyQr ? date('YYmmdd') : 2020202120202021;
        return openssl_encrypt($value, self::ENCRYPT_METHOD, $cryptKey, false, $iv);
    }

    public static function decrypt(string $cryptKey, string $value, $dailyQr = false)
    {
        $iv = $dailyQr ? date('YYmmdd') : 2020202120202021;
        return openssl_decrypt($value, self::ENCRYPT_METHOD, $cryptKey, false, $iv);
    }
}
