<?php declare(strict_types = 1);

namespace App\ViewData;

use App\Entity\Transaction;

class TransactionListView
{
    private $transactions;

    /**
     * @param Transaction[] $transactions
     */
    public function __construct(array $transactions)
    {
        $this->transactions = $transactions;
    }

    public function toArray()
    {
        return array_map(
            function (Transaction $transaction) {
                return [
                    'id' => $transaction->getId(),
                    'title' => $transaction->getTitle(),
                    'amount' => $transaction->getAmount(),
                    'type' => $transaction->getType(),
                    'account' => $transaction->getAccount(),
                ];
            },
            $this->transactions
        );
    }

}