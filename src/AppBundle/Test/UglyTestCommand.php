<?php

declare(strict_types=1);

namespace AppBundle\Test;

use AppBundle\UglyMail\UglyMailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UglyTestCommand extends Command
{
    private $uglyMailer;

    public function __construct(UglyMailer $uglyMailer)
    {
        $this->uglyMailer = $uglyMailer;
        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setName('mail:send:ugly')
            ->setDescription('Send ugly test mail.')
            ->setHelp('This command allows you to send mail in a ugly manner');
    }

    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->uglyMailer->sendForgottenPasswordEmail(1, '1234hash');
    }
}
