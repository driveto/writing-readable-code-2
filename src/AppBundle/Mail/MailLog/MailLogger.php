<?php

declare(strict_types=1);

namespace AppBundle\Mail\MailLog;

use Psr\Log\LoggerInterface;

class MailLogger
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function logSuccess(string $recipientEmails, string $undeliveredRecipientEmails): void
    {
        $this->logger->debug(
            sprintf(
                'SwiftMailer message sent to recipients: %s. Failed recipients: %s',
                $recipientEmails,
                $undeliveredRecipientEmails
            )
        );
    }

    public function logErrorAttempt(string $recipientEmails, string $message): void
    {
        $this->logger->critical(
            sprintf(
                'SwiftMailer could not sent message to recipients, will try again: %s, message: %s',
                $recipientEmails,
                $message
            )
        );
    }

    public function logError(string $recipientEmails, string $undeliveredRecipientEmails): void
    {
        $this->logger->critical(
            sprintf(
                'SwiftMailer failed to deliver to recipients: %s, undelivered recipient: %s',
                $recipientEmails,
                $undeliveredRecipientEmails
            )
        );
    }
}
