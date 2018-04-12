<?php

declare(strict_types=1);

namespace AppBundle\Test;

use AppBundle\Mail\Composer\RegistrationMailComposer;
use AppBundle\Mail\Dispatcher\MailDispatcher;
use AppBundle\User\MockUserRepository;
use AppBundle\User\Password\PasswordGetter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    private $registrationMailComposer;
    private $mockUserRepository;
    private $mailDispatcher;

    public function __construct(
        RegistrationMailComposer $registrationMailComposer,
        MockUserRepository $mockUserRepository,
        MailDispatcher $mailDispatcher
    ) {
        $this->registrationMailComposer = $registrationMailComposer;
        $this->mockUserRepository = $mockUserRepository;
        $this->mailDispatcher = $mailDispatcher;
        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setName('mail:send')
            ->setDescription('Send test mail.')
            ->setHelp('This command allows you to send mail');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $user = $this->mockUserRepository->getById(1);
        $email = $this->registrationMailComposer->composePasswordRecoveryEmail(
            $user, 'http://our-wonderful-web.com/password/recovery/1234hash'
        );

        $this->mailDispatcher->dispatchNewEmail($email);
    }
}
