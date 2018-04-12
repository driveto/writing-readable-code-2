<?php

declare(strict_types=1);

namespace AppBundle\Doctrine\Filter;

use DateTime;

abstract class AbstractCondition implements FilterInterface
{
    const PARAMETER_PREFIX = 'param';

    /** @var string */
    protected $name;
    /** @var mixed */
    protected $value;

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getAlias(): string
    {
        if ($this->value === null) {
            return lcfirst($this->name);
        }
        if ($this->value instanceof DateTime) {
            return lcfirst($this->name.md5((string) $this->value->getTimestamp()));
        }
        if (is_array($this->value) === true) {
            return lcfirst($this->name.md5(implode('-', $this->value)));
        }

        return lcfirst($this->name.md5((string) $this->value));
    }

    abstract public function getExpression(string $sqlAlias);
}
