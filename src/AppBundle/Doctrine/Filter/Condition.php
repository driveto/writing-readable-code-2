<?php

declare(strict_types=1);

namespace AppBundle\Doctrine\Filter;

use DateTime;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\Expr\Comparison;
use Exception;

class Condition extends AbstractCondition
{
    const COMPARISON_EQUALS = '=';
    const COMPARISON_EQ = self::COMPARISON_EQUALS;
    const COMPARISON_NEQ = '!=';
    const COMPARISON_GTE = '>=';
    const COMPARISON_GT = '>';
    const COMPARISON_LTE = '<=';
    const COMPARISON_LT = '<';

    private $comparisonType;

    /**
     * @param string $name
     * @param int|string|DateTime|bool $value
     * @param string $comparisonType = self::COMPARISON_EQUALS
     */
    public function __construct(string $name, $value, string $comparisonType = self::COMPARISON_EQUALS)
    {
        $this->name = $name;
        $this->value = $value;
        $this->comparisonType = $comparisonType;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpression(string $sqlAlias): Comparison
    {
        $comparisons = $this->getComparisonMappingsToExpression($sqlAlias);
        if (array_key_exists($this->comparisonType, $comparisons) === true) {
            return $comparisons[$this->comparisonType];
        }

        throw new Exception('Unresolved comparsionType: '.$this->comparisonType);
    }

    /**
     * @param string $sqlAlias
     * @return Comparison[]
     */
    private function getComparisonMappingsToExpression(string $sqlAlias): array
    {
        return [
            self::COMPARISON_LT => (new Expr())->lt($sqlAlias.'.'.$this->name, ':'.$this->getAlias()),
            self::COMPARISON_LTE => (new Expr())->lte($sqlAlias.'.'.$this->name, ':'.$this->getAlias()),
            self::COMPARISON_GT => (new Expr())->gt($sqlAlias.'.'.$this->name, ':'.$this->getAlias()),
            self::COMPARISON_GTE => (new Expr())->gte($sqlAlias.'.'.$this->name, ':'.$this->getAlias()),
            self::COMPARISON_NEQ => (new Expr())->neq($sqlAlias.'.'.$this->name, ':'.$this->getAlias()),
            self::COMPARISON_EQ => (new Expr())->eq($sqlAlias.'.'.$this->name, ':'.$this->getAlias()),
        ];
    }
}
