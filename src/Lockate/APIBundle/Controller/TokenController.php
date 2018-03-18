<?php

namespace Lockate\APIBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class TokenController extends Controller
{
    public function newTokenAction(Request $request) {

        $user = $this->getDoctrine()
            ->getRepository('LockateAPIBundle:User')
            ->findOneBy(['username' => $request->getUser()]);

        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $request->getPassword());

        //Lockate hardcoded custom header
        $custom_lockate_token = $request->headers->all()['x-auth-token'];
        if ($custom_lockate_token[0] !== 'schmier') {
            return new JsonResponse(
                array("message" => "Check custom header value")
            );
        }

        if (!$isValid) {
            return new JsonResponse(
                array("message" => "Invalid request for token")
            );
        }

        /* 1 hour */
        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode([
                'username' => $user->getUsername(),
                'exp' => time() + 3600 // 1 hour expiration
            ]);

        return new JsonResponse(['token' => $token]);
    }
}