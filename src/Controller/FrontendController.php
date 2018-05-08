<?php declare(strict_types = 1);

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class FrontendController extends Controller
{
    /** @Route("/{current}", name="home", requirements={"current": ".*"}) */
    public function home(AccountRepository $repository): Response
    {
        if (!$repository->hasAccounts()) {
            return $this->redirectToRoute('account_create');
        }

        return $this->render('home.html.twig', ['accounts' => $repository->findAll()]);
    }
}
