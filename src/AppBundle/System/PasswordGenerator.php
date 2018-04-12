<?php

declare(strict_types=1);

namespace AppBundle\System;

class PasswordGenerator
{
    const DEFAULT_LENGTH = 8;

    public function generate(int $length = self::DEFAULT_LENGTH): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?';

        return mb_substr(str_shuffle($chars), 0, $length);
    }
}
