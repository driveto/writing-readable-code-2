<?php

declare(strict_types=1);

namespace AppBundle\Mail\Dispatcher;

use AppBundle\Mail\Address\EmailAddress;
use AppBundle\Mail\Email;
use AppBundle\Mail\MailSender;
use AppBundle\MailQueue\Item\MailQueueItem;
use AppBundle\MailQueue\Item\MailQueueItemFacade;
use AppBundle\MailQueue\Item\MailQueueItemStatusEnum;
use AppBundle\System\DateTime\DateTimeFactory;
use Doctrine\ORM\EntityManager;

class MailDispatcher
{
    private $mailQueueItemFacade;
    private $dateTimeFactory;
    private $entityManager;
    private $mailSender;

    public function __construct(
        MailQueueItemFacade $mailQueueItemFacade,
        DateTimeFactory $dateTimeFactory,
        EntityManager $entityManager,
        MailSender $mailSender
    ) {
        $this->mailQueueItemFacade = $mailQueueItemFacade;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->entityManager = $entityManager;
        $this->mailSender = $mailSender;
    }

    public function dispatchNewEmail(Email $email): void
    {
        foreach ($email->getTo() as $toAddress) {
            $this->dispatchNewEmailToRecipient($email, $toAddress);
        }
    }

    private function dispatchNewEmailToRecipient(Email $email, EmailAddress $recipient): void
    {
        $this->entityManager->transactional(function () use ($email, $recipient) {
            $this->mailQueueItemFacade->save(
                $this->getMailQueueItem($email, $recipient)
            );
            $this->mailSender->send($email);
        });
    }

    private function getMailQueueItem(Email $email, EmailAddress $recipient): MailQueueItem
    {
        $fromEmail = $email->getFrom()->getEmail();
        $fromName = $fromEmail;
        if ($email->getFrom()->hasName() === true) {
            $fromName = $email->getFrom()->getName();
        }

        $recipientEmail = $recipient->getEmail();
        $recipientName = $recipientEmail;
        if ($recipient->hasName() === true) {
            $recipientName = $recipient->getName();
        }

        $replyToEmail = $email->getReplyTo()->getEmail();
        $replyToName = $replyToEmail;
        if ($email->getReplyTo()->hasName() === true) {
            $replyToName = $email->getReplyTo()->getName();
        }

        return new MailQueueItem(
            $fromName,
            $fromEmail,
            $recipientName,
            $recipientEmail,
            $email->getSubject(),
            $email->getBodyText(),
            $email->getBodyHtml(),
            $replyToName,
            $replyToEmail,
            MailQueueItemStatusEnum::MESSAGE_SENT,
            0,
            $this->dateTimeFactory->createNow()
        );
    }
}
