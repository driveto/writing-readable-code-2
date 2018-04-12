<?php

declare(strict_types=1);

namespace AppBundle\Mail\Template;

use Enum\AbstractEnum;

class MailBlockEnum extends AbstractEnum
{
    const BLOCK_NAME_HTML_CONTENT = 'htmlContent';
    const BLOCK_NAME_TEXT_CONTENT = 'bodyText';
    const BLOCK_NAME_SUBJECT = 'subject';
}
