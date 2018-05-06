<?php declare(strict_types = 1);

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Account\AmountCalculator;
use App\Account\Form\AccountFormHandler;
use App\Account\Form\AccountType;
use App\Entity\Account;
use Symfony\Component\HttpFoundation\Request;
use App\Transaction\Form\TransactionFormHandler;
use App\Transaction\Form\TransactionType;

class FrontendController extends Controller
{
    /** @Route("/", name="home") */
    public function home(AccountRepository $repository): Response
    {
        if (!$repository->hasAccounts()) {
            return $this->redirectToRoute('account_create');
        }

        return $this->render('home.html.twig', ['accounts' => $repository->findAll()]);
    }

    /** @Route("/demo", name="demo") */
    public function demo()
    {
        return $this->render('demo.html.twig');
    }
    
    /** @Route("/account/create", name="account_create") */
    public function createAccount(Request $request, AccountFormHandler $formHandler): Response
    {
        $form = $this->createForm(AccountType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formHandler->handle($form->getData());

            return $this->redirectToRoute('home');
        }

        return $this->render('account/add.html.twig', ['form' => $form->createView()]);
    }

    /** @Route("/transaction/add", methods={"POST"}, name="transaction_add") */
    public function addTransaction(Request $request, TransactionFormHandler $formHandler, AccountRepository $accountRepository)
    {
        if (!$accountRepository->hasAccounts()) {
            return $this->redirectToRoute('account_create');
        }

        $form = $this->getTransactionForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formHandler->handle($form->getData());

            return $this->redirectToRoute('home');
        }

        return $this->render('transaction/add.html.twig', ['form' => $form->createView()]);
    }

    public function transactionForm()
    {
        return $this->render('transaction/_form.html.twig', ['form' => $this->getTransactionForm()->createView()]);
    }

    private function getTransactionForm()
    {
        return $this->createForm(TransactionType::class, null, ['action' => $this->generateUrl('transaction_add')]);
    }

    public function totalAmounts(AccountRepository $repository, AmountCalculator $calculator): Response
    {
        $accounts = array_map(
            function (Account $account) use ($calculator) {
                return [
                    'name' => $account->getName(),
                    'total' => $calculator->calculateTotal($account),
                ];
            },
            $repository->findAll()
        );

        return $this->render('account/total_amounts.html.twig', ['accounts' => $accounts]);
    }
}
