<?php

declare(strict_types=1);

namespace AppBundle\Doctrine\Filter;

use DateTime;
use Doctrine\ORM\Query\Expr;

class ConditionInArray extends AbstractCondition
{
    /**
     * @param string $name
     * @param int[]|string[]|DateTime[] $value
     */
    public function __construct(string $name, array $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpression(string $sqlAlias)
    {
        $expression = new Expr();

        return $expression->in($sqlAlias.'.'.$this->name, ':'.$this->getAlias());
    }
}
