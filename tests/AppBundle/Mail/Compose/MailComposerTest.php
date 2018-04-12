<?php

declare(strict_types=1);

namespace AppBundle\Mail\Compose;

use AppBundle\Mail\Address\EmailAddress;
use AppBundle\Mail\Email;
use AppBundle\Mail\Template\MailTemplate;
use AppBundle\MailQueue\Message\AllowedMailManagersEnum;
use AppBundle\MailQueue\Message\AllowedSendersEnum;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MailComposerTest extends KernelTestCase
{
    /** @var MailComposer */
    private $mailComposer;

    public function setUp(): void
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $container = static::$kernel->getContainer();

        $this->mailComposer = $container->get(MailComposer::class);
    }

    /**
     * @param AllowedSendersEnum $sender
     * @param AllowedMailManagersEnum $replyTo
     * @param EmailAddress $to
     * @param MailTemplate $mailTemplate
     * @param Email $expectedEmail
     * @dataProvider provideMailComposerArguments
     */
    public function testComposeEmailToClients(
        AllowedSendersEnum $sender,
        AllowedMailManagersEnum $replyTo,
        EmailAddress $to,
        MailTemplate $mailTemplate,
        Email $expectedEmail
    ): void {
        $actualEmail = $this->mailComposer->composeEmailToClients(
            $sender,
            $replyTo,
            [$to],
            $mailTemplate
        );

        self::assertEquals(
            $expectedEmail->getSubject(),
            $actualEmail->getSubject()
        );
        self::assertEquals(
            $expectedEmail->getTo(),
            $actualEmail->getTo()
        );
        self::assertEquals(
            $expectedEmail->getFrom(),
            $actualEmail->getFrom()
        );
        self::assertEquals(
            $expectedEmail->getReplyTo(),
            $actualEmail->getReplyTo()
        );
        self::assertEquals(
            $expectedEmail->getBodyHtml(),
            $actualEmail->getBodyHtml()
        );
        self::assertEquals(
            $expectedEmail->getBodyText(),
            $actualEmail->getBodyText()
        );
    }

    public function provideMailComposerArguments(): array
    {
        return [
            [
                new AllowedSendersEnum(AllowedSendersEnum::SENDER_DEFAULT_CZ),
                new AllowedMailManagersEnum(AllowedMailManagersEnum::MANAGER_DEFAULT_CZ),
                new EmailAddress('anymail@myapp.cz', null),
                new MailTemplate(
                    'AppBundle::email.html.twig',
                    [
                        'subject' => 'test email subject',
                        'textBody' => 'text body',
                        'htmlBody' => '<b>html</b> body',
                    ]
                ),
                new Email(
                    new EmailAddress('no-reply@nase-firma.cz', 'Naše firma'),
                    [new EmailAddress('anymail@myapp.cz', null)],
                    '[pehapkari] [testing] test email subject',
                    'text body',
                    '<b>html</b> body',
                    new EmailAddress('mail@our-app.cz', 'Our app')
                ),
            ]
        ];
    }
}
