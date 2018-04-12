<?php

declare(strict_types=1);

namespace AppBundle\Mail;

use AppBundle\Mail\Address\EmailAddress;

class Email
{
    private $from;
    private $to;
    private $subject;
    private $bodyText;
    private $bodyHtml;
    private $replyTo;

    /**
     * @param EmailAddress $from
     * @param EmailAddress[] $to
     * @param string $subject
     * @param string $bodyText
     * @param string $bodyHtml
     * @param EmailAddress $replyTo
     */
    public function __construct(
        EmailAddress $from,
        array $to,
        string $subject,
        string $bodyText,
        string $bodyHtml,
        EmailAddress $replyTo
    ) {
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->bodyText = $bodyText;
        $this->bodyHtml = $bodyHtml;
        $this->replyTo = $replyTo;
    }

    public function getFrom(): EmailAddress
    {
        return $this->from;
    }

    /**
     * @return EmailAddress[]
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @return string[]
     */
    public function getToEmails(): array
    {
        $toEmails = [];
        foreach ($this->getTo() as $toAddress) {
            $toEmails[] = $toAddress->getEmail();
        }

        return $toEmails;
    }

    public function getToEmailsAsString(): string
    {
        return implode(', ', $this->getToEmails());
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getBodyText(): string
    {
        return $this->bodyText;
    }

    public function getBodyHtml(): string
    {
        return $this->bodyHtml;
    }

    public function getReplyTo(): EmailAddress
    {
        return $this->replyTo;
    }
}
