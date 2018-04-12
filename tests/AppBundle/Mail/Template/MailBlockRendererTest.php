<?php

declare(strict_types=1);

namespace AppBundle\Mail\Template;

use AppBundle\Mail\Address\EmailAddress;
use AppBundle\User\PasswordHash;
use AppBundle\User\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MailBlockRendererTest extends KernelTestCase
{
    /** @var MailBlockRenderer */
    private $mailBlockRenderer;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $container = static::$kernel->getContainer();

        $this->mailBlockRenderer = $container->get(MailBlockRenderer::class);
    }

    /**
     * @param MailTemplate $mailTemplate
     * @param MailBlockEnum $mailBlock
     * @param string $expectedBlockContents
     * @dataProvider templateBlockDataProvider
     */
    public function testRenderMailTemplateBlock(MailTemplate $mailTemplate, MailBlockEnum $mailBlock, string $expectedBlockContents)
    {
        $actualBlockContents = $this->mailBlockRenderer->renderMailTemplateBlock($mailTemplate, $mailBlock);

        self::assertEquals($expectedBlockContents, $actualBlockContents);
    }

    public function templateBlockDataProvider()
    {
        $defaultMailTemplate = new MailTemplate(
            'AppBundle::email.html.twig',
            [
                'subject' => 'testing subject',
                'htmlBody' => 'html <strong>text body</strong>',
                'textBody' => 'plain text body'
            ]
        );
        $registrationMailTemplate = new MailTemplate(
            'AppBundle::emails/password-recovery.html.twig',
            [
                'user' => new User(
                    new EmailAddress('email@domain.tld',
                        'Johnny Cash'
                    ),
                    new PasswordHash('hashhashhash')
                )
            ]
        );

        return [
            [
                $defaultMailTemplate,
                new MailBlockEnum(MailBlockEnum::BLOCK_NAME_SUBJECT),
                'testing subject'
            ],
            [
                $defaultMailTemplate,
                new MailBlockEnum(MailBlockEnum::BLOCK_NAME_TEXT_CONTENT),
                'plain text body'
            ],
            [
                $defaultMailTemplate,
                new MailBlockEnum(MailBlockEnum::BLOCK_NAME_HTML_CONTENT),
                'html <strong>text body</strong>'
            ],
            [
                $registrationMailTemplate,
                new MailBlockEnum(MailBlockEnum::BLOCK_NAME_SUBJECT),
                'Ahoj, Johnny Cash, obnov si heslo!'
            ],
        ];
    }
}
