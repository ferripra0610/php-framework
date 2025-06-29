<?php 

namespace App\Core;

class JwtHelper
{
    private static $secretKey = 'mysecretkey';

    public static function generateToken($payload)
    {
        $header = base64_encode(json_encode(['typ' => 'JWT', 'alg' => 'HS256']));
        $payload = base64_encode(json_encode($payload));
        $signature = hash_hmac('sha256', "$header.$payload", self::$secretKey, true);
        $signature = base64_encode($signature);
        return "$header.$payload.$signature";
    }

    public static function validateToken($token)
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }

        list($header, $payload, $signature) = $parts;
        $validSignature = base64_encode(hash_hmac('sha256', "$header.$payload", self::$secretKey, true));

        if ($signature !== $validSignature) {
            return false;
        }

        return json_decode(base64_decode($payload), true);
    }
}
