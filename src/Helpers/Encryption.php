<?php

namespace Elab\Lite\Helpers;

class Encryption
{
    private const METHOD = 'aes-128-cbc';
    private const IV = 'fffff96c027b6efd';
    private const KEY = 'FFFF5iTIYNl42LO9';

    public static function decrypt($data, $key = self::KEY, $method = self::METHOD, $iv = self::IV)
    {
        return openssl_decrypt(base64_decode($data), self::METHOD, $key,0, self::IV);
    }

    public static function encrypt($data, $key = self::KEY, $method = self::METHOD, $iv = self::IV)
    {
        return base64_encode(openssl_encrypt($data, self::METHOD, $key,0, self::IV));
    }
}
