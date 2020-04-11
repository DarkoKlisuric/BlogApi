<?php

namespace App\Security;

/**
 * Class TokenGenerator
 * @package App\Security
 */
class TokenGenerator
{
    private const ALPHABET = 'QWERTZUIOPASDFGHJKLYXCVBNMqwertzuiopasdfghjklyxcvbnm1234567890';

    /**
     * @param int $length
     * @return string
     */
    public function getRandomSecureToken(int $length = 30): string
    {
        $token = '';

        $maxNumber = strlen(self::ALPHABET);

        for ($i = 0; $i < $length; $i++) {
            try {
                $token .= self::ALPHABET[random_int(0, $maxNumber - 1)];
            } catch (\Exception $e) {
                $e->getMessage();
            }
        }

        return $token;
    }
}