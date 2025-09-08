<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private $key;
    private $ttl;

    public function __construct()
    {
        $this->key = env('JWT_SECRET');
        $this->ttl = env('JWT_TTL');
    }

    public function generateToken($user)
    {
        $payload = [
            'iss' => "laravel-jwt",      // emissor
            'sub' => $user->id,          // subject (ID do usuário)
            'iat' => time(),             // emitido em
            'exp' => time() + $this->ttl // expiração
        ];

        return JWT::encode($payload, $this->key, 'HS256');
    }

    public function decodeToken($token)
    {
        return JWT::decode($token, new Key($this->key, 'HS256'));
    }
}
