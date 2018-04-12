<?php

declare(strict_types=1);

namespace AppBundle\MailQueue\Item;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Table(name="mail_queue_item")
 * @ORM\Entity(repositoryClass="AppBundle\MailQueue\Item\MailQueueItemRepository")
 */
class MailQueueItem
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Type("int")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     */
    private $fromName;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     */
    private $fromEmail;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     */
    private $toName;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     */
    private $toEmail;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     */
    private $subject;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Type("string")
     */
    private $body;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Type("string")
     */
    private $htmlBody;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     */
    private $replyToName;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     */
    private $replyToEmail;

    /**
     * @ORM\Column(type="string")
     * @Serializer\Type("string")
     */
    private $status;

    /**
     * @ORM\Column(type="date")
     * @Serializer\Type("DateTime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Type("int")
     */
    private $errorCount;

    public function __construct(
        string $fromName,
        string $fromEmail,
        string $toName,
        string $toEmail,
        string $subject,
        string $body,
        string $htmlBody,
        string $replyToName,
        string $replyToEmail,
        string $status,
        int $errorCount,
        DateTime $createdAt
    ) {
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
        $this->toName = $toName;
        $this->toEmail = $toEmail;
        $this->subject = $subject;
        $this->body = $body;
        $this->htmlBody = $htmlBody;
        $this->replyToName = $replyToName;
        $this->replyToEmail = $replyToEmail;
        $this->status = $status;
        $this->errorCount = $errorCount;
        $this->createdAt = $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function hasFromName(): bool
    {
        return $this->fromName !== null;
    }

    public function getFromName(): string
    {
        return $this->fromName;
    }

    public function setFromName(string $fromName): void
    {
        $this->fromName = $fromName;
    }

    public function getFromEmail(): string
    {
        return $this->fromEmail;
    }

    public function setFromEmail(string $fromEmail): void
    {
        $this->fromEmail = $fromEmail;
    }

    public function hasToName(): bool
    {
        return $this->toName !== null;
    }

    public function getToName(): string
    {
        return $this->toName;
    }

    public function setToName(string $toName): void
    {
        $this->toName = $toName;
    }

    public function getToEmail(): string
    {
        return $this->toEmail;
    }

    public function setToEmail(string $toEmail): void
    {
        $this->toEmail = $toEmail;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getHtmlBody(): string
    {
        return $this->htmlBody;
    }

    public function setHtmlBody(string $htmlBody): void
    {
        $this->htmlBody = $htmlBody;
    }

    public function hasReplyToName(): bool
    {
        return $this->replyToName !== null;
    }

    public function getReplyToName(): string
    {
        return $this->replyToName;
    }

    public function setReplyToName(string $replyToName): void
    {
        $this->replyToName = $replyToName;
    }

    public function getReplyToEmail(): string
    {
        return $this->replyToEmail;
    }

    public function setReplyToEmail(string $replyToEmail): void
    {
        $this->replyToEmail = $replyToEmail;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getErrorCount(): int
    {
        return $this->errorCount;
    }

    public function setErrorCount(int $errorCount): void
    {
        $this->errorCount = $errorCount;
    }

    public function incrementErrorCount(): void
    {
        $this->errorCount++;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
