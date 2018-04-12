<?php

declare(strict_types=1);

namespace AppBundle\Mail\MailLog;

use Doctrine\ORM\EntityManager;

class MailLogRepository
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveOne(MailLog $mailQueueItem): MailLog
    {
        $this->entityManager->persist($mailQueueItem);
        $this->entityManager->flush($mailQueueItem);

        return $mailQueueItem;
    }
}
