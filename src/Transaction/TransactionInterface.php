<?php declare(strict_types = 1);

namespace App\Transaction;

use App\Account\Account;

interface TransactionInterface
{
    const TYPE_SAVE = 'save';
    const TYPE_SPEND = 'spend';
    
    public function apply(Account $account): Account;
    public function getAmount(): int;
    public function getTitle(): string;
    public function getType(): string;
}