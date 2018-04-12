<?php

declare(strict_types=1);

namespace AppBundle\Mail;

use AppBundle\Mail\MailLog\MailLogger;
use AppBundle\Mail\SwiftMessage\SwiftMessagePreparer;
use Swift_Mailer;

class MailSender
{
    private $swiftMailer;
    private $mailLogger;
    private $swiftMessagePreparer;

    public function __construct(Swift_Mailer $swiftMailer, MailLogger $mailLogger, SwiftMessagePreparer $swiftMessagePreparer)
    {
        $this->swiftMailer = $swiftMailer;
        $this->mailLogger = $mailLogger;
        $this->swiftMessagePreparer = $swiftMessagePreparer;
    }

    /**
     * @param Email $email
     * @return string[]
     * @throws MessageCannotBeSentException
     */
    public function send(Email $email): array
    {
        $failedRecipientEmails = [];
        $swiftMessage = $this->swiftMessagePreparer->prepareSwiftMessage($email);
        if ($this->swiftMailer->send($swiftMessage, $failedRecipientEmails) === 0) {
            $this->mailLogger->logError(
                $email->getToEmailsAsString(),
                implode(', ', $failedRecipientEmails)
            );

            throw new MessageCannotBeSentException(
                'Could not deliver email to any of the recipients: '.implode(', ', $failedRecipientEmails)
            );
        }
        $this->mailLogger->logSuccess(
            $email->getToEmailsAsString(),
            implode(', ', $failedRecipientEmails)
        );

        return array_diff($email->getToEmails(), $failedRecipientEmails);
    }
}
