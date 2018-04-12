<?php

declare(strict_types=1);

namespace AppBundle\MailQueue\Item;

use AppBundle\Doctrine\Filter\Condition;
use AppBundle\Doctrine\Filter\ConditionInArray;
use AppBundle\System\DateTime\DateTimeFactory;

class MailQueueItemFilterFactory
{
    /**
     * @var DateTimeFactory
     */
    private $dateTimeFactory;

    public function __construct(DateTimeFactory $dateTimeFactory)
    {
        $this->dateTimeFactory = $dateTimeFactory;
    }

    public function getById(int $id): Condition
    {
        return new Condition('id', $id);
    }

    public function getByAge(int $hoursBack): Condition
    {
        $dateFrom = $this->dateTimeFactory->createModifiedFromNow(sprintf('-%d hours', $hoursBack));

        return new Condition('createdAt', $dateFrom, Condition::COMPARISON_GTE);
    }

    /**
     * @param string[] $statuses
     * @return ConditionInArray
     */
    public function getByStatuses(array $statuses): ConditionInArray
    {
        return new ConditionInArray('status', $statuses);
    }
}
