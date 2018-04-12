<?php

declare(strict_types=1);

namespace AppBundle\Mail\MailLog;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="mail_log")
 */
class MailLog
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $fromName;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $fromEmail;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $toName;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $toEmail;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $subject;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $bodyText;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $bodyHtml;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $replyToName;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $replyToEmail;

    /**
     * @var DateTime
     * @ORM\Column(type="date")
     */
    private $sendDate;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $sendState;

    public function __construct(
        string $fromName,
        string $fromEmail,
        string $toName,
        string $toEmail,
        string $subject,
        string $bodyText,
        string $bodyHtml,
        string $replyToName,
        string $replyToEmail,
        DateTime $sendDate,
        string $sendState
    ) {
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
        $this->toName = $toName;
        $this->toEmail = $toEmail;
        $this->subject = $subject;
        $this->bodyText = $bodyText;
        $this->bodyHtml = $bodyHtml;
        $this->replyToName = $replyToName;
        $this->replyToEmail = $replyToEmail;
        $this->sendDate = $sendDate;
        $this->sendState = $sendState;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFromName(): string
    {
        return $this->fromName;
    }

    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    public function getToName(): string
    {
        return $this->toName;
    }

    public function getToEmail(): string
    {
        return $this->toEmail;
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

    public function getReplyToName(): string
    {
        return $this->replyToName;
    }

    public function getReplyToEmail(): string
    {
        return $this->replyToEmail;
    }

    public function getSendDate(): DateTime
    {
        return $this->sendDate;
    }

    public function getSendState(): string
    {
        return $this->sendState;
    }
}
