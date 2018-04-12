<?php

declare(strict_types=1);

namespace AppBundle\Mail\Address;

class EmailAddress
{
    private $email;
    private $name;

    public function __construct(string $email, ?string $name)
    {
        $this->email = $email;
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function hasName(): bool
    {
        return $this->name !== null;
    }
}
