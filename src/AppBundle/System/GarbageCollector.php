<?php

declare(strict_types=1);

namespace AppBundle\System;

class GarbageCollector
{
    public function enable()
    {
        gc_enable();
    }

    public function disable()
    {
        $this->collectCycles();
        gc_disable();
    }

    public function isEnabled()
    {
        return gc_enabled();
    }

    public function collectCycles()
    {
        return gc_collect_cycles();
    }
}
