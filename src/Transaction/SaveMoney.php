<?php declare(strict_types = 1);

namespace App\Transaction;

use App\Account\Account;

class SaveMoney implements TransactionInterface
{
    private $amount;
    private $title;

    public function __construct(int $amount, string $title = '')
    {
        $this->amount = $amount;
        $this->title = $title;
    }

    public function apply(Account $account): Account
    {
        return new Account($account->getAmount() + $this->amount);
    }
    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getType(): string
    {
        return TransactionInterface::TYPE_SAVE;
    }
}
