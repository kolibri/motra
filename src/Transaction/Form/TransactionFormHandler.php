<?php declare(strict_types = 1);

namespace App\Transaction\Form;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;

class TransactionFormHandler
{
    private $repository;

    public function __construct(TransactionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(TransactionData $data)
    {
        $this->repository->add(new Transaction($data->title, (int)($data->amount * 100), $data->type, $data->account));
    }
}