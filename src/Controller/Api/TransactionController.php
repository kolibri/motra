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

/** @Route("/transaction", name="api_transaction_") */
class TransactionController
{
    /** @Route("/", name="add", methods={"PUT","POST"}) */
    public function add(
        Request $request,
        TransactionRepository $repository,
        AccountRepository $accountRepository,
        RouterInterface $router
    ) {
        $data = json_decode($request->getContent(), true);
        $account = $accountRepository->findById($data['account']);

        return $this->handleNewTransaction(
            $repository,
            $router,
            $data['title'],
            (int)($data['amount'] * 100),
            $data['type'],
            $account
        );
    }

    /** @Route("/add/{id}", name="add_to_account", methods={"PUT","POST"}) */
    public function addToAccount(
        Request $request,
        Account $account,
        TransactionRepository $repository,
        RouterInterface $router
    ) {
        $data = json_decode($request->getContent(), true);

        return $this->handleNewTransaction(
            $repository,
            $router,
            $data['title'],
            (int)($data['amount'] * 100),
            $data['type'],
            $account
        );
    }

    /** @Route("/{id}", name="view", methods={"GET"}) */
    public function view(Transaction $transaction, NormalizerInterface $normalizer): JsonResponse
    {
        return new JsonResponse($normalizer->normalize($transaction, 'json'));
    }

    /** @Route("/{id}", name="delete", methods={"DELETE"}) */
    public function delete(Transaction $transaction, TransactionRepository $repository): JsonResponse
    {
        $repository->delete($transaction);

        return new JsonResponse(['deleted' => true]);
    }

    private function handleNewTransaction(
        TransactionRepository $repository,
        RouterInterface $router,
        string $title,
        int $amount,
        string $type,
        Account $account
    ) {
        $transaction = new Transaction($title, $amount, $type, $account);
        $repository->add($transaction);

        return new RedirectResponse($router->generate('api_transaction_view', ['id' => $transaction->getId()]));
    }
}
