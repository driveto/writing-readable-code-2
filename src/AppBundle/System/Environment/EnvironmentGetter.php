<?php

declare(strict_types=1);

namespace AppBundle\System\Environment;

class EnvironmentGetter
{
    private $environment;

    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function isProductionEnvironment(): bool
    {
        return $this->getEnvironment() === EnvironmentEnum::ENV_PROD;
    }

    public function isTestEnvironment(): bool
    {
        return $this->getEnvironment() === EnvironmentEnum::ENV_TEST;
    }

    public function isDevelopmentEnvironment(): bool
    {
        return $this->getEnvironment() === EnvironmentEnum::ENV_DEV;
    }
}
