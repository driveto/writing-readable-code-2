<?php

declare(strict_types=1);

namespace AppBundle\System\Environment;

use Enum\AbstractEnum;

class EnvironmentEnum extends AbstractEnum
{
    const ENV_PROD = 'prod';
    const ENV_DEV = 'dev';
    const ENV_TEST = 'test';
}
