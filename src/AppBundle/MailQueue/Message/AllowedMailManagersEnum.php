<?php

declare(strict_types=1);

namespace AppBundle\MailQueue\Message;

use Enum\AbstractEnum;
use Exception;

class AllowedMailManagersEnum extends AbstractEnum
{
    const MANAGER_DEFAULT_CZ = 'mail@our-app.cz';
    const MANAGER_DEVELOPMENT_CZ = 'dev@our-app.cz';

    public function getLabel()
    {
        $labelValues = $this->getLabelValues();
        if (array_key_exists($this->getValue(), $labelValues) === true) {
            return $labelValues[$this->getValue()];
        }

        throw new Exception(
            'Undefined label for mail manager: '.$this->getValue()
        );
    }

    private function getLabelValues()
    {
        return [
            self::MANAGER_DEFAULT_CZ => 'Our app',
            self::MANAGER_DEVELOPMENT_CZ => 'Our app DEV TEAM',
        ];
    }
}
