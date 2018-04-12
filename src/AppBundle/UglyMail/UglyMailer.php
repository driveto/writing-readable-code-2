<?php

declare(strict_types=1);

namespace AppBundle\UglyMail;

use AppBundle\Mail\MailLog\MailLogFacade;
use AppBundle\MailQueue\Item\MailQueueItem;
use AppBundle\MailQueue\Item\MailQueueItemFacade;
use AppBundle\MailQueue\Item\MailQueueItemStatusEnum;
use AppBundle\User\MockUserRepository;
use Psr\Log\LoggerInterface;

class UglyMailer
{
    protected $mailer;
    protected $twig;
    protected $userRepository;
    protected $mailLogFacade;
    protected $mailQueueItemFacade;
    protected $options;
    protected $logger;

    public function __construct(
        \Swift_Mailer $mailer,
        \Twig_Environment $twig,
        MockUserRepository $userRepository,
        MailLogFacade $mailLogFacade,
        MailQueueItemFacade $mailQueueItemFacade,
        LoggerInterface $logger,
        array $options
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->userRepository = $userRepository;
        $this->mailLogFacade = $mailLogFacade;
        $this->mailQueueItemFacade = $mailQueueItemFacade;
        $this->logger = $logger;
        $this->options = $options;
    }

    /**
     * @param int $userId
     * @param string $hash
     * @return int
     */
    public function sendForgottenPasswordEmail(int $userId, string $hash): int
    {
        $user = $this->userRepository->getById($userId);
        $templateParameters = [
            'userEmail' => $user->getEmail()->getEmail(),
            'userSalutation' => $user->getSalutation(),
            'passwordRecoveryLink' => 'http://our-wonderful-web.com/password/recovery/'.$hash
        ];

        return $this->sendMessage(
            'AppBundle::emails/password-recovery.html.twig',
            $templateParameters,
            $this->options['sender']['order_saved'],
            $user->getEmail()->getEmail()
        );
    }

    /**
     * @param Checkout $checkout
     * @return int
     */
    public function sendOrderEmail(Checkout $checkout)
    {
        //FIXME - ***** ****, This is ugly. tracker ****
        $superChargeBundleItems = [];

        $templateParameters = [
            'checkout' => $checkout,
            'superChargeBundleItems' => $superChargeBundleItems,
            'clickAndCollectStore' => $checkout->getClickCollectCode() ? $this->storeRepository->findOneBy(['gxCode' => $checkout->getClickCollectGxCode()]) : null,
        ];

        if ($pdf = $this->pdfGenerator->generateFromTemplate(':pdf/OrderRecapitulation:index.html.twig', $templateParameters)) {
            $attachment = $this->getPdfAttachment($checkout->getOrderNumber(), $pdf);
        } else {
            $attachment = null;
        }

        $specialBundleItem = null;
        /** @var Checkout $checkout */
        foreach ($checkout->getBundles() as $checkoutBundle) {
            /** @var CheckoutBundleItem $item */
            foreach ($checkoutBundle->getItems() as $item) {
                if (!is_null($item->getNumber()) && NumberType::TYPE_SOME === $item->getNumber()->getType()) {
                    $specialBundleItem = $item;
                    break;
                }
            }
        }

        if ((null !== $specialBundleItem) && !$specialBundleItem->getSomething()->getSomething()) {
            $templateName = 'AppBundle::emails/common.html.twig';
        } elseif ($checkout->getDeliveryType() && !$checkout->getDeliveryType()->isStore()) {
            $templateName = 'AppBundle::emails/special.html.twig';
        } else {
            $templateName = 'AppBundle::emails/some_other_default_template.html.twig';
        }

        return $this->sendMessage($templateName, $templateParameters, $this->options['sender']['order_saved'], $checkout->getMail(), $attachment ? [$attachment] : null);
    }

    /**
     * @param $templateName
     * @param $templateParameters
     * @param $fromEmail
     * @param $toEmail
     * @param array|null $attachments
     * @return int
     */
    protected function sendMessage($templateName, $templateParameters, $fromEmail, $toEmail, array $attachments = null): int
    {
        $templateParameters = $this->twig->mergeGlobals($templateParameters);
        $template = $this->twig->loadTemplate($templateName);
        $subject = $template->renderBlock('subject', $templateParameters);
        $textBody = $template->renderBlock('bodyText', $templateParameters);
        $htmlBody = $template->renderBlock('bodyHtml', $templateParameters);

        $send = false;
        if ($subject) {
            $message = (new \Swift_Message())
                ->setSubject($subject)
                ->setFrom($fromEmail)
                ->setTo($toEmail);

            if (!empty($htmlBody)) {
                $message->setBody($htmlBody, 'text/html')
                    ->addPart($textBody, 'text/plain');
            } else {
                $message->setBody($textBody);
            }

            if ($attachments) {
                foreach ($attachments as $attachment) {
                    $message->attach($attachment);
                }
            }
            $send = $this->mailer->send($message);
            $mailQueue = $this->mailQueueItemFacade->save(
                new MailQueueItem(
                    'noname - must be passed',
                    $fromEmail,
                    'noname - must be passed',
                    $toEmail,
                    $subject,
                    $textBody,
                    $htmlBody,
                    'no reply to given',
                    'no-reply-to-given',
                    MailQueueItemStatusEnum::MESSAGE_SENT,
                    0,
                    new \DateTime()
                )
            );
            $this->mailLogFacade->saveLogMailAttemptFromQueueItem($mailQueue, new \DateTime());
        } else {
            $this->logger->error('Unable to send mail: ' . $templateName);
        }

        return $send;
    }

    /**
     * @param $attachmentFileName
     * @param $content
     * @return \Swift_Mime_Attachment
     */
    protected function getPdfAttachment($attachmentFileName, $content)
    {
        return new \Swift_Attachment($content, sprintf('%s.pdf', $attachmentFileName), 'application/pdf');
    }
}
