<?php

namespace App\Controller;

use Exception;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login", methods={"POST", "OPTIONS"})
     * @param AuthenticationUtils $authUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();
        if($error === null){
            $user = $this->getUser();
            $builder = SerializerBuilder::create();
            $serializer = $builder->build();
            $context = SerializationContext::create()->enableMaxDepthChecks();
            if($user !== null)
                $json = $serializer->serialize($user, 'json', $context);
            else
                $json = "";
            return new JsonResponse($json, 200, [], true);
        }else{
            return new Response($error, 401);
        }
    }

    /**
     * @Route("/api/logout")
     */
    public function logout()
    {
        return new Response("", 204);
    }
}
