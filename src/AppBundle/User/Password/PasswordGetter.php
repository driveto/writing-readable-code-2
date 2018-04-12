<?php

declare(strict_types=1);

namespace AppBundle\User\Password;

use AppBundle\User\PasswordHash;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class PasswordGetter
{

    /** @var \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(PasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function hashPassword(string $password): PasswordHash
    {
        return new PasswordHash(
            $this->passwordEncoder->encodePassword($password, null)
        );
    }

}
