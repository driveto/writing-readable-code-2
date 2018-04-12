<?php

declare(strict_types=1);

namespace AppBundle\System\DateTime;

use DateTime;

class DateTimeFactory
{
    public static function static(): DateTimeFactory
    {
        return new self();
    }

    public function createNow(): DateTime
    {
        return $this->createDateTime('now');
    }

    public function createDateTime(string $time): DateTime
    {
        return new DateTime($time);
    }

    public function createModifiedFromNow($string): DateTime
    {
        return $this->createNow()->modify($string);
    }
}
