<?php declare(strict_types = 1);


namespace App\Controller;

use App\Transaction\Form\SpendMoneyFormHandler;
use App\Transaction\Form\SpendMoneyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/spend")
 */
class SpendMoneyController extends Controller
{
    /** @var SpendMoneyFormHandler */
    private $formHandler;

    public function __construct(SpendMoneyFormHandler $formHandler)
    {
        $this->formHandler = $formHandler;
    }
    /**
     * @Route("", methods={"POST"}, name="spend")
     */
    public function add(Request $request)
    {
        $form = $this->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->formHandler->handle($form->getData());

            return $this->redirectToRoute('home');
        }

        return $this->render('spend/add.html.twig', ['form' => $form->createView()]);
    }

    public function form()
    {
        return $this->render('spend/_form.html.twig', ['form' => $this->getForm()->createView()]);
    }

    private function getForm() {
        return $this->createForm(SpendMoneyType::class, null, ['action' => $this->generateUrl('spend')]);
    }
}