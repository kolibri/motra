<?php declare(strict_types = 1);

namespace App\Account;

use App\Repository\TransactionRepository;

class AccountLoader
{
    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function load()
    {
        $transactions = $this->transactionRepository->getAll();

        $account = new Account(0);

        dump($transactions);

        foreach ($transactions as $transaction) {
            $account = $transaction->apply($account);
        }

        return $account;
    }
}