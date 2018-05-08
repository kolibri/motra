<?php declare(strict_types = 1);

namespace App\Controller\Api;

use App\Account\AmountCalculator;
use App\Entity\Account;
use App\Repository\AccountRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/** @Route("/account", name="api_account_") */
class AccountController
{
    /** @Route("/list", name="list", methods={"GET"}) */
    public function list(AccountRepository $repository, AmountCalculator $calculator): JsonResponse
    {
        return new JsonResponse(
            [
                'accounts' =>
                    array_map(
                        function (Account $account) use ($calculator) {
                            return [
                                'id' => $account->getId(),
                                'name' => $account->getName(),
                                'total' => $calculator->calculateTotal($account),
                            ];
                        },
                        $repository->findAll()
                    ),
            ]
        );
    }

    /** @Route("/add", name="add", methods={"PUT","POST"}) */
    public function add(Request $request, AccountRepository $repository, RouterInterface $router)
    {
        $data = json_decode($request->getContent(), true);
        $account = new Account($data['name']);
        $repository->add($account);

        return new RedirectResponse($router->generate('api_account_view', ['id' => $account->getId()]));
    }

    /** @Route("/view/{id}", name="view", methods={"GET"}) */
    public function view(Account $account, AmountCalculator $calculator, RouterInterface $router)
    {
        return new JsonResponse(
            [
                'id' => $account->getId(),
                'name' => $account->getName(),
                'total' => $calculator->calculateTotal($account),
                '_self' => $router->generate('api_account_view', ['id' => $account->getId()]),
            ]
        );
    }
}