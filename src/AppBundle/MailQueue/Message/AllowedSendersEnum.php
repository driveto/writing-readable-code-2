<?php

declare(strict_types=1);

namespace AppBundle\MailQueue\Message;

use Enum\AbstractEnum;
use Exception;

class AllowedSendersEnum extends AbstractEnum
{
    const SENDER_DEFAULT_CZ = 'no-reply@nase-firma.cz';
    const SENDER_DEVELOPMENT_CZ = 'dev@nase-firma.cz';

    public function getLabel()
    {
        $labelValues = $this->getLabelValues();
        if (array_key_exists($this->getValue(), $labelValues) === true) {
            return $labelValues[$this->getValue()];
        }

        throw new Exception(
            'Undefined label for sender: '.$this->getValue()
        );
    }

    private function getLabelValues()
    {
        return [
            self::SENDER_DEFAULT_CZ => 'Naše firma',
            self::SENDER_DEVELOPMENT_CZ => 'Naše firma DEV TEAM',
        ];
    }
}
