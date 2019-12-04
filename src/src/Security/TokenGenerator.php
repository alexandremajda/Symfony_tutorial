<?php

namespace App\Security;

/**
 * A simple class which provide a randomly generated secure token
 */
class TokenGenerator
{
    private const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    public function getRandomSecureToken(int $length = 30): string
    {
        $token = '';
        $maxNumber = strlen(self::ALPHABET);

        for ($i = 0; $i < $length; $i++)
            $token .= self::ALPHABET[random_int(0, $maxNumber - 1)];

        return $token;
    }
}
