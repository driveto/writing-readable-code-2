<?php

declare(strict_types=1);

namespace AppBundle\System;

class MemoryLimitGetter
{
    public function getMemoryLimit(): int
    {
        $memoryLimitInBytes = 999 * 1024 * 1024 * 1024 * 1024;
        $memoryLimit = ini_get('memory_limit');
        $matchResult = preg_match('/^(\d+)(.)$/', $memoryLimit, $matches);
        if ($matchResult !== false && $matchResult > 0) {
            if ($matches[2] === 'G') {
                $memoryLimitInBytes = $matches[1] * 1024 * 1024 * 1024;
            } elseif ($matches[2] === 'M') {
                $memoryLimitInBytes = $matches[1] * 1024 * 1024;
            } elseif ($matches[2] === 'K') {
                $memoryLimitInBytes = $matches[1] * 1024;
            }
        }

        return $memoryLimitInBytes;
    }

    public function getMemoryUsage(): int
    {
        return memory_get_usage(true);
    }

    public function isMemoryLimitAlmostReached()
    {
        $ninetyPercentOfMemoryLimit = ceil(0.9 * $this->getMemoryLimit());
        if ($this->getMemoryUsage() >= $ninetyPercentOfMemoryLimit) {
            return true;
        }

        return false;
    }
}
