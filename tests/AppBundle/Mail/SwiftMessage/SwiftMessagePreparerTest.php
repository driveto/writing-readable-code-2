<?php

declare(strict_types=1);

namespace AppBundle\Mail\SwiftMessage;

use AppBundle\Mail\Address\EmailAddress;
use AppBundle\Mail\Email;
use AppBundle\MailQueue\Message\AllowedSendersEnum;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SwiftMessagePreparerTest extends KernelTestCase
{
    /** @var SwiftMessagePreparer */
    private $swiftMessagePreparer;

    public function setUp(): void
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $container = static::$kernel->getContainer();

        $this->swiftMessagePreparer = $container->get(SwiftMessagePreparer::class);
    }

    /**
     * @param Email $email
     * @param Swift_Message $expectedSwiftMessage
     * @dataProvider provideEmails
     */
    public function testPrepareSwiftMessage(Email $email, Swift_Message $expectedSwiftMessage): void
    {
        $actualSwiftMessage = $this->swiftMessagePreparer->prepareSwiftMessage($email);

        self::assertEquals(
            $expectedSwiftMessage->getTo(),
            $actualSwiftMessage->getTo()
        );
        self::assertEquals(
            $expectedSwiftMessage->getFrom(),
            $actualSwiftMessage->getFrom()
        );
        self::assertEquals(
            $expectedSwiftMessage->getReplyTo(),
            $actualSwiftMessage->getReplyTo()
        );
        self::assertEquals(
            $expectedSwiftMessage->getBody(),
            $actualSwiftMessage->getBody()
        );
        self::assertEquals(
            $expectedSwiftMessage->getSubject(),
            $actualSwiftMessage->getSubject()
        );
    }

    public function provideEmails(): array
    {
        $fromDevelopmentWithLove = new AllowedSendersEnum(AllowedSendersEnum::SENDER_DEVELOPMENT_CZ);
        $swiftMessage = new Swift_Message();
        $swiftMessage
            ->setSubject('subject')
            ->setFrom($fromDevelopmentWithLove->getValue())
            ->setReplyTo('reply-to@test.spaceflow')
            ->setTo('to@test.spaceflow')
            ->setBody('<strong>test</strong>', 'text/html')
            ->addPart('plain text body', 'text/plain');

        $swiftMessageWithNames = new Swift_Message();
        $swiftMessageWithNames
            ->setSubject('subject')
            ->setFrom($fromDevelopmentWithLove->getValue(), $fromDevelopmentWithLove->getLabel())
            ->setReplyTo('reply-to@test.spaceflow', 'Reply-To')
            ->setTo('to@test.spaceflow', 'Recipient')
            ->setBody('<strong>test</strong>', 'text/html')
            ->addPart('plain text body', 'text/plain');

        return [
            [
                'email' => new Email(
                    new EmailAddress($fromDevelopmentWithLove->getValue(), null),
                    [new EmailAddress('to@test.spaceflow', null)],
                    'subject',
                    'plain text body',
                    '<strong>test</strong>',
                    new EmailAddress('reply-to@test.spaceflow', null)
                ),
                'expectedSwiftMessage' => $swiftMessage
            ],
            [
                'email' => new Email(
                    new EmailAddress($fromDevelopmentWithLove->getValue(), $fromDevelopmentWithLove->getLabel()),
                    [new EmailAddress('to@test.spaceflow', 'Recipient')],
                    'subject',
                    'plain text body',
                    '<strong>test</strong>',
                    new EmailAddress('reply-to@test.spaceflow', 'Reply-To')
                ),
                'expectedSwiftMessage' => $swiftMessageWithNames
            ],
        ];
    }
}
