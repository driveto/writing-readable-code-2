<?php

declare(strict_types=1);

namespace AppBundle\Mail;

use Exception;

class MessageCannotBeSentException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
