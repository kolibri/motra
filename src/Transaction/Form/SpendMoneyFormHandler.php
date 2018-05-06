<?php declare(strict_types = 1);

namespace App\Transaction\Form;

use App\Repository\TransactionRepository;
use App\Transaction\SpendMoney;

class SpendMoneyFormHandler
{
    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRespository)
    {
        $this->transactionRepository = $transactionRespository;
    }

    public function handle(SpendMoneyData $data)
    {
        $this->transactionRepository->add(new SpendMoney((int) $data->amount * 100, $data->title));
    }
}