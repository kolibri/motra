<?php declare(strict_types = 1);

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class FrontendController extends Controller
{
    /** 
     * @Route("/", name="home", methods={"GET"})
     * @Route("/app/{current}", name="home_app", requirements={"current": ".*"}, methods={"GET"}) 
     */
    public function home(AccountRepository $repository): Response
    {
        return $this->render('home.html.twig', ['accounts' => $repository->findAll()]);
    }
}
