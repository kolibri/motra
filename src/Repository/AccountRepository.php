<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AccountRepository
{
    private $entityManager;
    private $transactionRepository;

    public function __construct(EntityManagerInterface $entityManager, TransactionRepository $transactionRepository)
    {
        $this->entityManager = $entityManager;
        $this->transactionRepository = $transactionRepository;
    }

    public function findAll(): array
    {
        return $this->getRepository()->findAll();
    }

    public function findById(int $id): Account
    {
        return  $this->getRepository()->find($id);
    }

    public function hasAccounts()
    {
        return 0 < $this->getRepository()
            ->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()->getSingleScalarResult();
    }

    public function add(Account $account, bool $flush = true)
    {
        $this->entityManager->persist($account);
        $flush && $this->entityManager->flush();
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Account::class);
    }
}