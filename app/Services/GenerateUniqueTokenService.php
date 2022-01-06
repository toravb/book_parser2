<?php

namespace App\Services;

class GenerateUniqueTokenService
{
    /**
     * Create unique token or name
     *
     * @return string
     */
    public static function createTokenWithoutUserId(): string
    {
        return time() . bin2hex(random_bytes(16));
    }

    /**
     *  Create unique token or name with userID
     *
     * @param int $userId
     * @return string
     */
    public static function createTokenWithUserId(int $userId): string
    {
        return $userId . time() . bin2hex(random_bytes(16));
    }
}
