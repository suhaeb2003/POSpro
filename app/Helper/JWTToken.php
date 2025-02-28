<?php
namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    public static function CreateToken($userEmail, $userId)
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'www.pos.com',
            'iat' => time(),
            'exp' => time() + 60 * 60 * 12,
            'userEmail' => $userEmail,
            'userId' => $userId
        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    public static function SetTokenForSetPassword($userEmail, $userId)
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'www.pos.com',
            'iat' => time(),
            'exp' => time() + 60 * 5,
            'userEmail' => $userEmail,
            'userId' => '0'
        ];
        return JWT::encode($payload, $key, 'HS256');
    }
    public static function VerifyToken($token)
    {
        try {
            if (!$token) {
                return 'Unauthorized';
            } else {
                $key = env('JWT_KEY');
                $decode = JWT::decode($token, new Key($key, 'HS256'));
                return $decode;
            }


        } catch (Exception $e) {
            return 'Unauthorized';
        }

    }
}