<?php declare(strict_types = 1);

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiLoginGuard extends AbstractGuardAuthenticator
{
    /** @var RouterInterface */
    private $router;
    /** @var EncoderFactory */
    private $encoders;

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(['message' => 'Authentication required']);
    }

    public function supports(Request $request)
    {
        if (!$request->isMethod(Request::METHOD_POST)) {
            return false;
        }

        if (!$request->attributes->get('_route') === 'api_login') {
            return false;
        }

        return true;
    }

    public function getCredentials(Request $request)
    {
        return [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = $userProvider->loadUserByUsername($credentials['username']);

        if (!$user) {
            return;
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $encoder = $this->encoders->getEncoder(User::class);
        $encoder->isPasswordValid($user->getPassword(), $credentials['password'], $user->getSalt());
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return $this->start($request, $exception);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}