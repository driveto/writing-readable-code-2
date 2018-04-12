<?php

declare(strict_types=1);

namespace AppBundle\MailQueue\Item;

use Enum\AbstractEnum;

class MailQueueItemStatusEnum extends AbstractEnum
{
    const MESSAGE_NEW = 'new';
    const MESSAGE_ERROR = 'error';
    const MESSAGE_SENT = 'sent';
}
