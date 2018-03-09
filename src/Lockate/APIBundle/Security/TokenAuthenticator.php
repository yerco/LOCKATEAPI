<?php

namespace Lockate\APIBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request)
    {
        if (
            $request->headers->has("php-auth-user") &&
            $request->headers->has("php-auth-pw")) {
            return $request->headers->has('X-AUTH-TOKEN');
        }
        else {
            return false;
        }
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request)
    {
        return array(
            'username'  => $request->headers->get('php-auth-user'),
            'password'  => $request->headers->get('php-auth-pw'),
            'token'     => $request->headers->get('X-AUTH-TOKEN'),
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {

        $username = $credentials['username'];
        $password = $credentials['password'];
        $custom_header = $credentials['token'];

        if (null === $custom_header || null == $username || null == $password) {
            return;
        }

        // Yeke: my UserProvider here is `Lockate\APIBundle\Security\UserProvider`
        // if a User object, checkCredentials() is called
        // FIXME no a fixme but pay attention it is hardcoded
        if ($custom_header === 'schmier') {
            $user = $userProvider->loadUserByUsername($username);
            return $user;
        }
        else {
            return null;
        }
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        /**
         * Esto esta relacionado con app/config/security.yml donde tengo
         *      definido al usuario que sera autenticado (schmier en este caso)
         * security:
         *      providers:
         *              in_memory:
         *                  memory:
         *                      users:
         *                          schmier:
         *                              password: destruction
         *                              roles: 'ROLE_USER'
         */
        // check credentials - e.g. make sure the password is valid
        if ($credentials["username"] === $user->getUsername() ) {
            // return true to cause authentication success
            return true;
        }
        else {
            return false;
        }

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
        //return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            //'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
            'message'   => 'Check your credentials'
            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array(
            // you might translate this message
            'message' => 'Authentication Required'
        );
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
