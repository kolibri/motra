<?php declare(strict_types = 1);

namespace App\Account\Form;

use App\Entity\Account;
use App\Repository\AccountRepository;

class AccountFormHandler
{
    private $repository;

    public function __construct(AccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(AccountData $data)
    {
        $this->repository->add(new Account($data->name));
    }
}