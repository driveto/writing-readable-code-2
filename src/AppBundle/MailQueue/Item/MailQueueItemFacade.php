<?php

declare(strict_types=1);

namespace AppBundle\MailQueue\Item;

use Doctrine\ORM\EntityNotFoundException;

class MailQueueItemFacade
{
    private $mailQueueItemRepository;
    private $mailQueueItemFilterFactory;

    public function __construct(
        MailQueueItemRepository $mailQueueItemRepository,
        MailQueueItemFilterFactory $mailQueueItemFilterFactory
    ) {
        $this->mailQueueItemRepository = $mailQueueItemRepository;
        $this->mailQueueItemFilterFactory = $mailQueueItemFilterFactory;
    }

    /**
     * @return MailQueueItem[]
     */
    public function fetchAll(): array
    {
        return $this->mailQueueItemRepository->fetchAll();
    }

    public function getMailQueueItemById(int $id): MailQueueItem
    {
        $mailQueueItem = $this->mailQueueItemRepository->fetchOneByFilters([
            $this->mailQueueItemFilterFactory->getById($id),
        ]);

        if ($mailQueueItem === null) {
            throw new EntityNotFoundException(
                sprintf(
                    'Could not find %s by id: %s',
                    MailQueueItem::class,
                    var_export($id, true)
                )
            );
        }

        return $mailQueueItem;
    }

    public function save(MailQueueItem $mailQueueItem): MailQueueItem
    {
        return $this->mailQueueItemRepository->saveOne($mailQueueItem);
    }

    public function updateMailQueueItemStatus(MailQueueItem $mailQueueItem, MailQueueItemStatusEnum $sendStatus): MailQueueItem
    {
        $mailQueueItem->setStatus($sendStatus->getValue());

        return $this->mailQueueItemRepository->saveOne($mailQueueItem);
    }

    /**
     * @param int $age
     * @param array $statuses
     * @return MailQueueItem[]
     */
    public function getMailQueueItemsByAgeAndStatus(int $age, array $statuses): array
    {
        return $this->mailQueueItemRepository->fetchByFilters([
            $this->mailQueueItemFilterFactory->getByAge($age),
            $this->mailQueueItemFilterFactory->getByStatuses($statuses)
        ]);
    }

    /**
     * @param MailQueueItem[] $data
     */
    public function populateMailQueueItems(array $data): void
    {
        foreach ($data as $item) {
            $this->save($item);
        }
    }
}
