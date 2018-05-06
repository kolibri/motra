<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Transaction;
use App\Transaction\TransactionConverter;
use App\Transaction\TransactionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TransactionRepository
{
    private $entityManager;
    private $converter;

    public function __construct(EntityManagerInterface $entityManager, TransactionConverter $converter)
    {
        $this->entityManager = $entityManager;
        $this->converter = $converter;
    }

    /** @return TransactionInterface[] */
    public function getAll(): array
    {
        return array_map(
            function (Transaction $transaction) {
                return $this->converter->fromEntity($transaction);
            },
            $this->getRepository()->findAll()
        );
    }

    public function add(TransactionInterface $transaction, bool $flush = true)
    {
        $this->entityManager->persist($this->converter->toEntity($transaction));
        $flush && $this->entityManager->flush();
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Transaction::class);
    }
}
