<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Entity\MeetSlot;
use App\Entity\User;
use App\Service\InviteSender;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{


    /**
     * @Route("/api/users/test")
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function test(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
//        $message = new \Swift_Message("test");
//        $message->setFrom("atoevents@atoevents.ru")
//            ->setTo("nik_mak@bk.ru")
//            ->setBody( "test", 'text/html');
//        $mailer->send($message);
        dump($encoder->encodePassword($em->getRepository(User::class)->find(8), "sHhMcwIqdB"));
        return new JsonResponse();
    }

    /**
     * @Route("/api/users/create")
     * @param Request $request
     * @param InviteSender $sender
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function create(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setRole(User::USER);
        $user->setLogin("admin");
        $user->setName("admin");
        $user->setSurname("admin");
        $user->setPhone("");
        $user->setCompany("admin");
        $user->setPosition("admin");
        $user->setAccess(true);
        $user->setPassword($encoder->encodePassword($user, "admin"));
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        $builder = SerializerBuilder::create();
        $serializer = $builder->build();
        $context = SerializationContext::create()->enableMaxDepthChecks();
        $json = $serializer->serialize($user, 'json', $context);
        return new JsonResponse($json, 201, [], true);
    }


    /**
     * @Route("/api/users/add")
     * @param Request $request
     * @param InviteSender $sender
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function add(Request $request, InviteSender $sender, UserPasswordEncoderInterface $encoder)
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setRole(User::USER);
        $flag = $this->setBaseUserData($user, $data);
        if(!$flag)
            return new Response("Не все данные заполнены", 400);
        $password = User::generatePassword();
        $sender->sendPassword($user, $password);
        $user->setPassword($encoder->encodePassword($user, $password));
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        $builder = SerializerBuilder::create();
        $serializer = $builder->build();
        $context = SerializationContext::create()->enableMaxDepthChecks();
        $json = $serializer->serialize($user, 'json', $context);
        return new JsonResponse($json, 201, [], true);
    }

    /**
     * @Route("/api/users/get")
     * @return JsonResponse
     */
    public function getAll()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $builder = SerializerBuilder::create();
        $serializer = $builder->build();
        $context = SerializationContext::create()->enableMaxDepthChecks();
        $json = $serializer->serialize($users, 'json', $context);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/api/users/current")
     * @param Security $security
     * @return Response
     */
    public function getCurrent(Security $security)
    {
        if (!$security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new Response("Not Authorize", 401);
        }
        $user = $this->getUser();
        $builder = SerializerBuilder::create();
        $serializer = $builder->build();
        $context = SerializationContext::create()->enableMaxDepthChecks();
        $json = $serializer->serialize($user, 'json', $context);
        return new JsonResponse($json, 200, [], true);
    }

//    /**
//     * @Route("/api/test")
//     * @param EntityManagerInterface $em
//     * @param InviteSender $sender
//     * @return Response
//     * @throws Exception
//     */
//    public function test(EntityManagerInterface $em, InviteSender $sender)
//    {
//        $meet = new Meet();
//        $meet->setCreator($em->getRepository(User::class)->find(9));
//        $meet->setDate(new DateTime());
//        $meet->setGuest($em->getRepository(User::class)->find(12));
//        $meet->setSlot($em->getRepository(MeetSlot::class)->find(1));
//        $em->persist($meet);
//        $em->flush();
//        $sender->sendInvite($meet);
//        $builder = SerializerBuilder::create();
//        $serializer = $builder->build();
//        $context = SerializationContext::create()->enableMaxDepthChecks();
//        $json = $serializer->serialize($meet, 'json', $context);
//        return new JsonResponse($json, 200, [], true);
//    }


    /**
     * @Route("/api/users/invite/{g}/slot/{s}")
     * @param int $g
     * @param int $s
     * @param EntityManagerInterface $em
     * @param InviteSender $sender
     * @return Response
     * @throws Exception
     */
    public function invite($g, $s, EntityManagerInterface $em, InviteSender $sender)
    {
        if($this->getUser() == null || !$this->getUser()->getAccess()){
            return new Response("Access denied", 403);
        }
        $guest = $em->getRepository(User::class)->find($g);
        if($guest === null){
            return new Response("Not found", 404);
        }
        $slot = $em->getRepository(MeetSlot::class)->find($s);
        if($slot === null){
            return new Response("Not found", 404);
        }
        if(count($slot->getMeets()) >= 10){
            return new Response("Время полностью занято", 418);
        }
        if(count($slot->getMeets()) >= 10){
            return new Response("Время полностью занято", 418);
        }
        $creator = $this->getUser();
        foreach($creator->getCreatedMeets() as $meet){
            if($meet->getSlot()->getId() === $slot->getId()){
                return new Response("Вы уже создали встречу на это время", 418);
            }
        }
        if(count($creator->getCreatedMeets()) >= 3){
            return new Response("Количество созданных встреч исчерпано", 418);
        }
        $meet = new Meet();
        $meet->setCreator($creator);
        $meet->setDate(new DateTime());
        $meet->setGuest($guest);
        $meet->setSlot($slot);
        $em->persist($meet);
        $em->flush();
        $sender->sendInvite($meet);
        $builder = SerializerBuilder::create();
        $serializer = $builder->build();
        $context = SerializationContext::create()->enableMaxDepthChecks();
        $json = $serializer->serialize($meet, 'json', $context);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/api/users/{id}/edit")
     * @param Request $request
     * @param int $id
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Request $request, int $id, EntityManagerInterface $em)
    {
        $user = $em->getRepository(User::class)->find($id);
        if($user === null){
            return new Response("Not found", 404);
        }
        $data = json_decode($request->getContent(), true);
        $flag = $this->setBaseUserData($user, $data);
        if(!$flag)
            return new Response("Не все данные заполнены", 400);
        $em->flush();
        $builder = SerializerBuilder::create();
        $serializer = $builder->build();
        $context = SerializationContext::create()->enableMaxDepthChecks();
        $json = $serializer->serialize($user, 'json', $context);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/api/users/{id}/delete")
     * @param int $id
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(int $id, EntityManagerInterface $em)
    {
        $user = $em->getRepository(User::class)->find($id);
        if($user === null){
            return new Response("Not found", 404);
        }
        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();
        $builder = SerializerBuilder::create();
        $serializer = $builder->build();
        $context = SerializationContext::create()->enableMaxDepthChecks();
        $json = $serializer->serialize($user, 'json', $context);
        return new JsonResponse($json, 200, [], true);
    }

    protected function setBaseUserData(User $user, $data){
        if(!isset($data['login']) || !isset($data['name']) ||
            !isset($data['surname']) || !isset($data['phone']) ||
            !isset($data['company']) || !isset($data['position']) ||
            !isset($data['access']))
            return false;
        $user->setLogin($data['login']);
        $user->setName($data['name']);
        $user->setSurname($data['surname']);
        $user->setPhone($data['phone']);
        $user->setCompany($data['company']);
        $user->setPosition($data['position']);
        $user->setAccess($data['access']);
        return $data['login'] && $data['name'] && $data['surname'] &&
            $data['phone'] && $data['company'] && $data['position'];
    }
}
