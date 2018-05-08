<?php declare(strict_types = 1);

namespace App\Controller\Api;

use App\Account\AmountCalculator;
use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Repository\TransactionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;

/** @Route("/", name="api_") */
class ApiController
{
    /** @Route("", name="index", methods={"GET"}) */
    public function index(
        AccountRepository $accountRepository,
        TransactionRepository $transactionRepository,
        AmountCalculator $calculator,
        RouterInterface $router
    ): JsonResponse
    {
        return new JsonResponse(
            [
                'accounts' => array_map(
                        function (Account $account) use ($calculator, $transactionRepository, $router) {
                            return [
                                'id' => $account->getId(),
                                'name' => $account->getName(),
                                'total' => $calculator->calculateTotal($account),
                                'transactions' => $transactionRepository->findAllByAccount($account),
                                '_add_path' => $router->generate('api_transaction_add', ['id' => $account->getId()]),
                            ];
                        },
                        $accountRepository->findAll()
                    ),
            ]
        );
    }
}