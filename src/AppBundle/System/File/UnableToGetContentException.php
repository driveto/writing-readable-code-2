<?php

declare(strict_types=1);

namespace AppBundle\System\File;

use RuntimeException;

class UnableToGetContentException extends RuntimeException
{
    public function __construct($path, $errorMessage)
    {
        parent::__construct(sprintf('Path: %s, ErrorMessage: %s', $path, $errorMessage));
    }
}
