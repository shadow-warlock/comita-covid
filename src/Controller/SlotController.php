<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Entity\MeetSlot;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SlotController extends AbstractController
{

    /**
     * @Route("/api/slots/get")
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws Exception
     */
    public function getAll(EntityManagerInterface $em)
    {
        $em->getRepository(Meet::class)->deleteByDate((new DateTime())->setTimestamp((new DateTime())->getTimestamp()-24*60*60));
        $slots = $em->getRepository(MeetSlot::class)->findAll();
        $builder = SerializerBuilder::create();
        $serializer = $builder->build();
        $context = SerializationContext::create()->enableMaxDepthChecks();
        $json = $serializer->serialize($slots, 'json', $context);
        return new JsonResponse($json, 200, [], true);
    }

}
