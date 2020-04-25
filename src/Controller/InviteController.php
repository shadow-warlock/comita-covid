<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Service\InviteSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InviteController extends AbstractController
{
    /**
     * @Route("/api/invite/{id}/confirm")
     * @param $id
     * @param EntityManagerInterface $em
     * @param InviteSender $sender
     * @return Response
     */
    public function confirm($id, EntityManagerInterface $em, InviteSender $sender)
    {
        $meet = $em->getRepository(Meet::class)->find($id);
        if($this->getUser() === null) {
            return new Response("Вы не авторизированы", 401);
        }
        if($this->getUser()->getId() !== $meet->getGuest()->getId()){
            return new Response("Access denied", 403);
        }
        $meet->setStatus(Meet::CONFIRM);
        $em->flush();
        $sender->sendInviteResponse($meet);
        $sender->sendInviteResponseControl($meet);
        return new Response("Принято", 200);
    }

    /**
     * @Route("/api/invite/{id}/fail")
     * @param $id
     * @param EntityManagerInterface $em
     * @param InviteSender $sender
     * @return Response
     */
    public function fail($id, EntityManagerInterface $em, InviteSender $sender)
    {
        $meet = $em->getRepository(Meet::class)->find($id);
        if($this->getUser() === null) {
            return new Response("Вы не авторизированы", 401);
        }
        if($this->getUser()->getId() !== $meet->getGuest()->getId()) {
            return new Response("Access denied", 403);
        }
        $meet->setStatus(Meet::FAIL);
        $em->flush();
        $sender->sendInviteResponse($meet);
        return new Response("Отклонено", 200);
    }
}
