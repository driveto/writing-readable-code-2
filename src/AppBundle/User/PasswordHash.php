<?php

declare(strict_types=1);

namespace AppBundle\User;

class PasswordHash
{
    private $passwordHash;

    public function __construct(string $passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}
