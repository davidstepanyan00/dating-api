<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;


class AuthenticationController extends AbstractController
{
    /**
     * @Route("/api/token", name="get_token", methods={"POST"})
     */
    public function getToken(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        JWTTokenManagerInterface $JWTManager
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return new JsonResponse(['message' => 'Unable to find user'], 401);
        }

        $isValid = $passwordEncoder->isPasswordValid($user, $request->get('password'));

        if (!$isValid) {
            return new JsonResponse(['message' => 'Invalid credentials'], 401);
        }

        $token = $JWTManager->create($user);

        return new JsonResponse(['token' => $token]);
    }
}