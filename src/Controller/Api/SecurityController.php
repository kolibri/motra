<?php declare(strict_types = 1);

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/** @Route("/", name="api_security_") */
class SecurityController
{
    /** @Route("/register", name="register", methods={"PUT","POST"}) */
    public function register(
        Request $request,
        UserRepository $repository,
        RouterInterface $router,
        ValidatorInterface $validator,
        EncoderFactoryInterface $encoders
    ) {
        $data = json_decode($request->getContent(), true);

        $encoder = $encoders->getEncoder(User::class);
        $password = $encoder->encodePassword($data['password'], null);

        $user = new User($data['username'], $password, $data['email']);

        $violations = $validator->validate($user);

        if (0 < count($violations)) {
            return new JsonResponse(
                [
                    'errors' => array_map(
                        function (ConstraintViolation $violation) {
                            return $violation->getMessage();
                        },
                        $violations
                    ),
                ]
            );
        }

        $repository->add($user);

        return new RedirectResponse($router->generate('api_user_view', ['id' => $user->getId()]));
    }

    /** @Route("/check_login", name="check_login") */
    public function checkLogin(AuthorizationChecker $checker)
    {
        if ($checker->isGranted('ROLE_USER')) {
            return new JsonResponse(['success']);
        }

        return new JsonResponse(['error' => 'not authenticated'], 403);
    }
}
