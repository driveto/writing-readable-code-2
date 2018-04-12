<?php

declare(strict_types=1);

namespace AppBundle\System\File;

class FileContentGetter
{
    public function getContent($path)
    {
        // @codingStandardsIgnoreLine
        $content = @file_get_contents($path);
        if ($content === false) {
            $error = error_get_last();
            $errorMessage = $error['message'];

            throw new UnableToGetContentException($path, $errorMessage);
        }

        return $content;
    }
}
