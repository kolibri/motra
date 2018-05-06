<?php declare(strict_types = 1);


namespace App\Controller;


use App\Account\Account;
use App\Account\AccountLoader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountInfoController extends Controller
{
    private $loader;

    public function __construct(AccountLoader $loader)
    {
        $this->loader = $loader;
    }


    public function totalAmount()
    {
        $account = $this->loader->load();

        return $this->render('total_amount.html.twig', ['total_amount' => $account->getAmount()]);
    }
}