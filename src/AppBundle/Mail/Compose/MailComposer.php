<?php

declare(strict_types=1);

namespace AppBundle\Mail\Compose;

use AppBundle\Mail\Address\EmailAddress;
use AppBundle\Mail\Email;
use AppBundle\Mail\Template\MailBlockRenderer;
use AppBundle\Mail\Template\MailTemplate;
use AppBundle\MailQueue\Message\AllowedMailManagersEnum;
use AppBundle\MailQueue\Message\AllowedSendersEnum;
use AppBundle\System\Environment\EnvironmentGetter;

class MailComposer
{
    const TEST_ENV_MAIL_TAGS = ['pehapkari', 'testing'];

    private $mailBlockRenderer;
    private $environmentGetter;

    public function __construct(MailBlockRenderer $mailBlockRenderer, EnvironmentGetter $environmentGetter)
    {
        $this->mailBlockRenderer = $mailBlockRenderer;
        $this->environmentGetter = $environmentGetter;
    }

    public function composeEmailToClients(
        AllowedSendersEnum $from,
        AllowedMailManagersEnum $replyTo,
        array $to,
        MailTemplate $mailTemplate
    ): Email {
        return $this->composeEmailToClientsWithReplyTo(
            $from,
            new EmailAddress(
                $replyTo->getValue(),
                $replyTo->getLabel()
            ),
            $to,
            $mailTemplate
        );
    }

    public function composeEmailToClientsWithReplyTo(
        AllowedSendersEnum $from,
        EmailAddress $replyTo,
        array $to,
        MailTemplate $mailTemplate
    ): Email {
        $subject = $this->mailBlockRenderer->renderMailSubject($mailTemplate);
        $htmlBody = $this->mailBlockRenderer->renderMailHtmlContent($mailTemplate);
        $textBody = $this->mailBlockRenderer->renderMailTextContent($mailTemplate);

        return new Email(
            new EmailAddress(
                $from->getValue(),
                $from->getLabel()
            ),
            $to,
            $this->getSubjectWithTags($subject),
            $textBody,
            $htmlBody,
            $replyTo
        );
    }

    private function getSubjectWithTags(string $subject): string
    {
        if ($this->environmentGetter->isTestEnvironment() === true) {
            $tags = array_map(
                function ($tag) {
                    return '['.$tag.']';
                },
                self::TEST_ENV_MAIL_TAGS
            );

            return implode(' ', $tags).' '.$subject;
        }

        return $subject;
    }
}
