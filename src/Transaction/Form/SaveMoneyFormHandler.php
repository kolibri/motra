<?php declare(strict_types = 1);

namespace App\Transaction\Form;

use App\Repository\TransactionRepository;
use App\Transaction\SaveMoney;

class SaveMoneyFormHandler
{
    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRespository)
    {
        $this->transactionRepository = $transactionRespository;
    }

    public function handle(SaveMoneyData $data)
    {
        $this->transactionRepository->add(new SaveMoney((int) $data->amount * 100, $data->title));
    }
}