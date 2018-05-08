<?php declare(strict_types = 1);

namespace App\Controller\Api;

use App\Entity\Account;
use App\Entity\Transaction;
use App\Repository\AccountRepository;
use App\Repository\TransactionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/** @Route("/account", name="api_account_") */
class AccountController
{
    /** @Route("/", name="list", methods={"GET"}) */
    public function list(AccountRepository $repository, NormalizerInterface $normalizer): JsonResponse
    {
        return new JsonResponse(
            [
                'accounts' =>
                    array_map(
                        function (Account $account) use ($normalizer) {
                            return $normalizer->normalize($account, 'json');
                        },
                        $repository->findAll()
                    ),
            ]
        );
    }

    /** @Route("/", name="add", methods={"PUT","POST"}) */
    public function add(Request $request, AccountRepository $repository, RouterInterface $router)
    {
        $data = json_decode($request->getContent(), true);
        $account = new Account($data['name']);
        $repository->add($account);

        return new RedirectResponse($router->generate('api_account_view', ['id' => $account->getId()]));
    }

    /** @Route("/{id}", name="view", methods={"GET"}) */
    public function view(Account $account, TransactionRepository $repository, NormalizerInterface $normalizer)
    {
        return new JsonResponse(
            [
                'account' => $normalizer->normalize($account, 'json'),
                'transactions' => array_map(
                    function (Transaction $transaction) use ($normalizer) {
                        return $normalizer->normalize($transaction, 'json');
                    },
                    $repository->findAllByAccount($account)
                ),
            ]
        );
    }
}