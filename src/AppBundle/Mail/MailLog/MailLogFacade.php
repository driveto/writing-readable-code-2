<?php

declare(strict_types=1);

namespace AppBundle\Mail\MailLog;

use AppBundle\MailQueue\Item\MailQueueItem;
use DateTime;

class MailLogFacade
{
    private $mailLogRepository;

    public function __construct(MailLogRepository $mailLogRepository)
    {
        $this->mailLogRepository = $mailLogRepository;
    }

    public function saveLogMailAttemptFromQueueItem(MailQueueItem $mailQueueItem, DateTime $sendDate): MailLog
    {
        $fromName = '';
        if ($mailQueueItem->hasFromName() === true) {
            $fromName = $mailQueueItem->getFromName();
        }
        $toName = '';
        if ($mailQueueItem->hasToName() === true) {
            $toName = $mailQueueItem->getToName();
        }
        $replyToName = '';
        if ($mailQueueItem->hasReplyToName() === true) {
            $replyToName = $mailQueueItem->getReplyToName();
        }
        $emailLog = new MailLog(
            $fromName,
            $mailQueueItem->getFromEmail(),
            $toName,
            $mailQueueItem->getToEmail(),
            $mailQueueItem->getSubject(),
            $mailQueueItem->getBody(),
            $mailQueueItem->getHtmlBody(),
            $replyToName,
            $mailQueueItem->getReplyToEmail(),
            $sendDate,
            $mailQueueItem->getStatus()
        );

        return $this->mailLogRepository->saveOne($emailLog);
    }
}
