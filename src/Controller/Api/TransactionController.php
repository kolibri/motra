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

/** @Route("/transaction", name="api_transaction_") */
class TransactionController
{
    /** @Route("/add", name="add", methods={"PUT","POST"}) */
    public function add(Request $request, TransactionRepository $repository, AccountRepository $accountRepository, RouterInterface $router)
    {
        $data = $request->request->get('transaction');
        $account = $accountRepository->findById($data['account']);
        $transaction = new Transaction($data['title'], (int)($data['amount'] * 100), $data['type'], $account);
        $repository->add($transaction);

        return new RedirectResponse($router->generate('api_transaction_view', ['id' => $transaction->getId()]));
    }

    /** @Route("/add/{id}", name="add", methods={"PUT","POST"}) */
    public function addToAccount(Request $request, Account $account, TransactionRepository $repository, RouterInterface $router)
    {
        $data = json_decode($request->getContent(), true);
        $transaction = new Transaction($data['title'], (int)($data['amount'] * 100), $data['type'], $account);
        $repository->add($transaction);

        return new RedirectResponse($router->generate('api_index'));
    }
    
    /** @Route("/list", name="list", methods={"GET"}) */
    public function list(TransactionRepository $repository, RouterInterface $router): JsonResponse
    {
        return new JsonResponse(
            array_map(
                function (Transaction $transaction) use ($router) {
                    return [
                        'id' => $transaction->getId(),
                        'title' => $transaction->getTitle(),
                        'amount' => $transaction->getAmount(),
                        'type' => $transaction->getType(),
                        '_link' => $router->generate('api_transaction_view', ['id' => $transaction->getId()]),
                        '_self' => $router->generate('api_transaction_list'),
                    ];
                },
                $repository->findAll()
            )
        );
    }

    /** @Route("/view/{id}", name="view", methods={"GET"}) */
    public function view(Transaction $transaction, RouterInterface $router): JsonResponse
    {
        return new JsonResponse(
            [
                'id' => $transaction->getId(),
                'title' => $transaction->getTitle(),
                'amount' => $transaction->getAmount(),
                'type' => $transaction->getType(),
                'account' => $transaction->getAccount()->getName(),
                '_self' => $router->generate('api_transaction_view', ['id' => $transaction->getId()]),
            ]
        );
    }

    /** @Route("/delete/{id}", name="edit", methods={"POST"}) */
    public function delete(Transaction $transaction, TransactionRepository $repository): JsonResponse
    {
        $repository->delete($transaction);

        return new JsonResponse(['deleted' => true]);
    }
}
