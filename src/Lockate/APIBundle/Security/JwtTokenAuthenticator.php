<?php

namespace Lockate\APIBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    private $jwtEncoder;
    private $em;
    private $container;

    public function __construct(
        JWTEncoderInterface $jwtEncoder,
        EntityManagerInterface $em,
        Container $container
    )
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->em = $em;
        $this->container= $container;
    }

    public function supports(Request $request)
    {
        if ($request->get('_route') == 'lockate_site_homepage') {
            return false;
        }
        else {
            return true;
        }
    }

    public function getCredentials(Request $request) {

        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );
        $token = $extractor->extract($request);

        $user = $this->em
            ->getRepository('LockateAPIBundle:User')
            ->findOneBy(['username' => $request->getUser()]);

        if ($request->get('_route') === 'lockate_basic_auth_api_last_events') {
            return;
        }
        // Important: Only to get tokens route and password
        if (isset($user) && $request->get('_route') === 'lockate_api_tokens') {;
            $isValid = $this->container->get('security.password_encoder')
                ->isPasswordValid($user, $request->getPassword());

            if ($isValid) {
                return;
            }
        }

        // this sends you out
        if (!$token) {
            return true;
            //return null;
        }
        return $token;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $data = $this->jwtEncoder->decode($credentials);
        }
        catch (\Exception $e) {
            // jump to onAuthenticationFailure
            return null;
        }

        if ($data === false) {
            $data = array('message'   => 'Check your credentials');

            return new JsonResponse($data, Response::HTTP_FORBIDDEN);
            //throw new CustomUserMessageAuthenticationException('Invalid Token');
        }

        $username = $data['username'];
        return $this->em
            ->getRepository('LockateAPIBundle:User')
            ->findOneBy(['username' => $username]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ) {
        $data = array('message'   => 'Check your auth credentials or token');

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    ) {
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function start(
        Request $request,
        AuthenticationException $authException = null
    ) {
        return new JsonResponse([
            'error' => 'auth required'
        ], 401);
    }

}
