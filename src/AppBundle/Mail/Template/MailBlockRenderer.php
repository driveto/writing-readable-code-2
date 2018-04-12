<?php

declare(strict_types=1);

namespace AppBundle\Mail\Template;

use Throwable;
use Twig\Environment;

class MailBlockRenderer
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function renderMailSubject(MailTemplate $mailTemplate): string
    {
        return $this->renderMailTemplateBlock($mailTemplate, new MailBlockEnum(MailBlockEnum::BLOCK_NAME_SUBJECT));
    }

    public function renderMailTextContent(MailTemplate $mailTemplate): string
    {
        return $this->renderMailTemplateBlock($mailTemplate, new MailBlockEnum(MailBlockEnum::BLOCK_NAME_TEXT_CONTENT));
    }

    public function renderMailHtmlContent(MailTemplate $mailTemplate): string
    {
        return $this->renderMailTemplateBlock($mailTemplate, new MailBlockEnum(MailBlockEnum::BLOCK_NAME_HTML_CONTENT));
    }

    public function renderMailTemplateBlock(MailTemplate $mailTemplate, MailBlockEnum $mailBlock): string
    {
        try {
            $template = $this->twig->load($mailTemplate->getTemplateName());
            $templateVariables = $mailTemplate->getTemplateVariables();

            return $template->renderBlock($mailBlock->getValue(), $this->twig->mergeGlobals($templateVariables));
        } catch (Throwable $t) {
            throw new TemplateCouldNotBeRenderedException(
                'Could not render mail twig template: '.$mailTemplate->getTemplateName(),
                0,
                $t
            );
        }
    }
}
