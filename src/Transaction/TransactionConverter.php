<?php declare(strict_types = 1);

namespace App\Transaction;

use App\Entity\Transaction as Entity;

class TransactionConverter
{
    public function fromEntity(Entity $entity): TransactionInterface
    {
        if (TransactionInterface::TYPE_SPEND === $entity->getType()) {
            return new SpendMoney($entity->getAmount());
        }

        if (TransactionInterface::TYPE_SAVE === $entity->getType()) {
            return new SaveMoney($entity->getAmount());
        }

        throw new \RuntimeException(sprintf('unknown Entity type "%s"', $entity->getType()));
    }

    public function toEntity(TransactionInterface $entity): Entity
    {
        return new Entity($entity->getTitle(), $entity->getAmount(), $entity->getType());
    }
}