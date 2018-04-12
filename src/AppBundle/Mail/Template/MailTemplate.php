<?php

declare(strict_types=1);

namespace AppBundle\Mail\Template;

class MailTemplate
{
    private $templateName;
    private $templateVariables;

    public function __construct(
        string $templateName,
        array $templateVariables
    ) {
        $this->templateName = $templateName;
        $this->templateVariables = $templateVariables;
    }

    public function getTemplateName(): string
    {
        return $this->templateName;
    }

    /**
     * @return string[]
     */
    public function getTemplateVariables(): array
    {
        return $this->templateVariables;
    }
}
