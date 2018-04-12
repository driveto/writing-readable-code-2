<?php

declare(strict_types=1);

namespace AppBundle\User;

use AppBundle\Mail\Address\EmailAddress;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @Serializer\Type("int")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    public function __construct(EmailAddress $emailAddress, ?PasswordHash $passwordHash)
    {
        $this->email = $emailAddress->getEmail();
        if ($emailAddress->hasName() === true) {
            $this->name = $emailAddress->getName();
        }
        if ($passwordHash !== null) {
            $this->password = $passwordHash->getPasswordHash();
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSalutation(): string
    {
       return $this->hasName() ? $this->getName() : 'Unknown warrior';
    }

    public function hasName(): bool
    {
        return $this->name !== null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function hasEmail(): bool
    {
        return $this->email !== null;
    }

    public function getEmail(): EmailAddress
    {
        return new EmailAddress($this->email, null);
    }

    public function setEmail(EmailAddress $emailAddress): void
    {
        $this->email = $emailAddress->getEmail();
    }

    public function hasPassword(): bool
    {
        return $this->password !== null;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(PasswordHash $password): void
    {
        $this->password = $password->getPasswordHash();
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * @return null|string
     */
    public function getSalt()
    {
        return $this->hasPassword() ? '{'.$this->getPassword().'#'.$this->getEmail()->getEmail().'}' : null;
    }

    public function getUsername(): string
    {
        return $this->getEmail()->getEmail();
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
