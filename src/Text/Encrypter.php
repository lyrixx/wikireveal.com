<?php

namespace App\Text;

class Encrypter
{
    public function encrypt(string $value, string $password): array
    {
        $salt = openssl_random_pseudo_bytes(8);
        $salted = '';
        $dx = '';
        while (\strlen($salted) < 48) {
            $dx = md5($dx.$password.$salt, true);
            $salted .= $dx;
        }
        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);
        $encrypted_data = openssl_encrypt($value, 'aes-256-cbc', $key, true, $iv);
        $data = [
            'ct' => base64_encode($encrypted_data),
            'iv' => bin2hex($iv),
            's' => bin2hex($salt),
        ];

        return $data;
    }
}
