<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiSecurityController extends AbstractController
{
    /**
     * @Route("/api/login", name="api_login", methods={"POST"})
     */
    public function login(SerializerInterface $serializer)
    {
        $json = $serializer->serialize($this->getUser(), 'json', [
            'groups' => [
                'user:read'
            ]
        ]);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/api/logout", name="api_logout")
     */
    public function logout()
    {
    }
}
