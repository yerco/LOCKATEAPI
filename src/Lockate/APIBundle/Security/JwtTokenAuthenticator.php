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

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    private $jwtEncoder;
    private $em;

    public function __construct(JWTEncoderInterface $jwtEncoder, EntityManagerInterface $em)
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->em = $em;
    }

    public function getCredentials(Request $request) {

        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );
        $token = $extractor->extract($request);
        if (!$token) {
            return;
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
        $data = array('message'   => 'Check your credentials and/or token');

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    ) {
        //echo "\n\n\n\n\n\n\nonAuthenticationSuccess\n\n\n";
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
        //echo "\n\n\n\n\n\n\nSTART\n\n\n";
        return new JsonResponse([
            'error' => 'auth required'
        ], 401);
    }

}
