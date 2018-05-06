<?php declare(strict_types = 1);

namespace App\Account;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;

class AmountCalculator
{
    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function calculateTotal(Account $account): int
    {
        $transactions = $this->transactionRepository->findAllByAccount($account);

        $amount = 0;

        foreach ($transactions as $transaction) {
            if (Transaction::TYPE_SAVE === $transaction->getType()) {
                $amount += $transaction->getAmount();
                continue;
            }

            if (Transaction::TYPE_SPEND === $transaction->getType()) {
                $amount -= $transaction->getAmount();
                continue;
            }

            throw new \RuntimeException(sprintf('invalid transaction type "%s"', $transaction->getType()));
        }

        return $amount;
    }
}