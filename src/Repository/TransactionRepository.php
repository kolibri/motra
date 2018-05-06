<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TransactionRepository
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /** @return Transaction[] */
    public function findAll(): array
    {
        return $this->getRepository()->findAll();
    }

    /** @return Transaction[] */
    public function findAllByAccount(Account $account)
    {
        return $this->getRepository()->createQueryBuilder('t')
            ->where('t.account = :account')
            ->setParameter('account', $account)
            ->getQuery()
            ->getResult();
    }

    public function add(Transaction $transaction, bool $flush = true)
    {
        $this->entityManager->persist($transaction);
        $flush && $this->entityManager->flush();
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Transaction::class);
    }
}
