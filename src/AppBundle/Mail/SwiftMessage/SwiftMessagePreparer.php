<?php

declare(strict_types=1);

namespace AppBundle\Mail\SwiftMessage;

use AppBundle\MailQueue\Message\AllowedSendersEnum;
use AppBundle\Mail\Email;
use AppBundle\Mail\MessageCannotBeSentException;
use Swift_Message;

class SwiftMessagePreparer
{
    public function prepareSwiftMessage(Email $email): Swift_Message
    {
        $fromEmail = $email->getFrom()->getEmail();
        $fromName = null;
        if ($email->getFrom()->hasName() === true) {
            $fromName = $email->getFrom()->getName();
        }
        $toEmailWithNames = [];
        foreach ($email->getTo() as $toAddress) {
            if ($toAddress->hasName() === true) {
                $toEmailWithNames[$toAddress->getEmail()] = $toAddress->getName();
            } else {
                $toEmailWithNames[] = $toAddress->getEmail();
            }
        }
        $replyToEmail = $email->getReplyTo()->getEmail();
        $replyToName = null;
        if ($email->getReplyTo()->hasName() === true) {
            $replyToName = $email->getReplyTo()->getName();
        }

        if (AllowedSendersEnum::hasValue($fromEmail) === true) {
            $swiftMessage = new Swift_Message();
            $swiftMessage
                ->setSubject($email->getSubject())
                ->setFrom($fromEmail, $fromName)
                ->setReplyTo($replyToEmail, $replyToName)
                ->setTo($toEmailWithNames)
                ->setBody(
                    $email->getBodyHtml(),
                    'text/html'
                )
                ->addPart(
                    $email->getBodyHtml(),
                    'text/plain'
                );

            return $swiftMessage;
        }

        throw new MessageCannotBeSentException('Unresolved sender: '.$fromEmail);
    }
}
