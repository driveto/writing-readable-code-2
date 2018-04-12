<?php

declare(strict_types=1);

namespace AppBundle\MailQueue\Item;

use AppBundle\Doctrine\Filter\AbstractCondition;
use AppBundle\Doctrine\Filter\FilterInterface;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;

class MailQueueItemRepository
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityClassName(): string
    {
        return MailQueueItem::class;
    }

    /**
     * @return MailQueueItem[]
     */
    public function fetchAll(): array
    {
        return $this->fetchByFilters([]);
    }

    public function saveOne(MailQueueItem $mailQueueItem): MailQueueItem
    {
        $this->entityManager->persist($mailQueueItem);
        $this->entityManager->flush($mailQueueItem);

        return $mailQueueItem;
    }

    /**
     * @param FilterInterface[] $filters
     * @return mixed
     * @throws NonUniqueResultException|NoResultException
     */
    public function fetchOneByFilters(array $filters)
    {
        $builder = $this->createBuilderFromFilters($filters);
        $builder->select($this->getSqlAlias($this->getEntityClassName()));

        return $builder->getQuery()->getSingleResult();
    }

    /**
     * @param FilterInterface[] $filters
     * @param int $hydrationMode
     * @return mixed|null|array
     */
    public function fetchByFilters(array $filters, $hydrationMode = AbstractQuery::HYDRATE_OBJECT): array
    {
        $queryBuilder = $this->createBuilderFromFilters($filters);
        $queryBuilder->select($this->getSqlAlias($this->getEntityClassName()));

        try {
            return $queryBuilder->getQuery()->getResult($hydrationMode);
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param FilterInterface[] $filters
     * @return QueryBuilder
     */
    public function createBuilderFromFilters(array $filters): QueryBuilder
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from(
            $this->getEntityClassName(),
            $this->getSqlAlias($this->getEntityClassName())
        );
        $this->applyConditions($filters, $queryBuilder);

        return $queryBuilder;
    }

    /**
     * @param FilterInterface[] $filters
     * @param QueryBuilder $queryBuilder
     */
    private function applyConditions(array $filters, QueryBuilder $queryBuilder): void
    {
        foreach ($filters as $filter) {
            if ($filter instanceof AbstractCondition) {
                $queryBuilder->andWhere($filter->getExpression($this->getSqlAlias($this->getEntityClassName())));
                $queryBuilder->setParameter($filter->getAlias(), $filter->getValue());
            }
        }
    }

    private function getSqlAlias(string $entityClassNameOrAttributeName): string
    {
        return 'als'.(string) mb_strlen($entityClassNameOrAttributeName).mb_substr(md5($entityClassNameOrAttributeName), 0, 8);
    }
}

