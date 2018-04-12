<?php

declare(strict_types=1);

namespace AppBundle\User;

use AppBundle\Mail\Address\EmailAddress;
use AppBundle\User\Password\PasswordGetter;

class MockUserRepository
{
    private $passwordGetter;

    public function __construct(
        PasswordGetter $passwordGetter
    ) {
        $this->passwordGetter = $passwordGetter;
    }

    public function getById(int $id): User
    {
        if ($id > 10) {
            throw new \InvalidArgumentException('Invalid user id given - must not be larger than limit: ' . $id);
        }

        return new User(
            new EmailAddress('petr.bechyne@driveto.cz', null),
            $this->passwordGetter->hashPassword('test')
        );
    }
}
