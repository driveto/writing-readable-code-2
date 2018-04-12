<?php

declare(strict_types=1);

namespace AppBundle\Mail\Composer;

use AppBundle\Mail\Compose\MailComposer;
use AppBundle\Mail\Email;
use AppBundle\Mail\Template\MailTemplate;
use AppBundle\MailQueue\Message\AllowedMailManagersEnum;
use AppBundle\MailQueue\Message\AllowedSendersEnum;
use AppBundle\User\User;
use Exception;

class RegistrationMailComposer
{
    private $mailComposer;

    public function __construct(
        MailComposer $mailComposer
    ) {
        $this->mailComposer = $mailComposer;
    }

    /**
     * @param User $user
     * @param string $recoveryPasswordLink
     * @return Email
     * @throws Exception
     */
    public function composePasswordRecoveryEmail(User $user, string $recoveryPasswordLink): Email
    {
        return $this->mailComposer->composeEmailToClients(
            new AllowedSendersEnum(AllowedSendersEnum::SENDER_DEFAULT_CZ),
            new AllowedMailManagersEnum(AllowedMailManagersEnum::MANAGER_DEFAULT_CZ),
            [
                $user->getEmail()
            ],
            new MailTemplate(
                'AppBundle::emails/password-recovery.html.twig',
                [
                    'userEmail' => $user->getEmail()->getEmail(),
                    'userSalutation' => $user->getSalutation(),
                    'passwordRecoveryLink' => $recoveryPasswordLink
                ]
            )
        );
    }
}
