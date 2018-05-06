<?php declare(strict_types = 1);


namespace App\Controller;

use App\Transaction\Form\SaveMoneyFormHandler;
use App\Transaction\Form\SaveMoneyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/save")
 */
class SaveMoneyController extends Controller
{
    /** @var SaveMoneyFormHandler */
    private $formHandler;

    public function __construct(SaveMoneyFormHandler $formHandler)
    {
        $this->formHandler = $formHandler;
    }

    /**
     * @Route("", methods={"POST","GET"}, name="save")
     */
    public function add(Request $request)
    {
        $form = $this->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->formHandler->handle($form->getData());

            return $this->redirectToRoute('home');
        }

        return $this->render('save/add.html.twig', ['form' => $form->createView()]);
    }

    public function form()
    {
        return $this->render('save/_form.html.twig', ['form' => $this->getForm()->createView()]);
    }

    private function getForm()
    {
        return $this->createForm(SaveMoneyType::class, null, ['action' => $this->generateUrl('save')]);
    }
}