<?php

namespace App\Controller;

use App\Entity\Meet;
use App\Entity\MeetSlot;
use App\Entity\User;
use App\Service\InviteSender;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Hoa\Compiler\Visitor\Dump;
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
     * @Route("/api/users/repair/{mail}")
     * @param Request $request
     * @param $mail
     * @param UserPasswordEncoderInterface $encoder
     * @param InviteSender $sender
     * @return Response
     */
    public function passfix(Request $request, $mail, InviteSender $sender)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findBy(["login"=>$mail]);
        if(count($users) == 0)
            return new Response("Mail not found", 404);
        $user = $users[0];
        $sender->sendPasswordRepair($user);
        return new Response("На указанную почту отправлена ссылка для восстановления пароля");
    }


    /**
     * @Route("/api/users/test")
     * @param Request $request
     * @param $mail
     * @param UserPasswordEncoderInterface $encoder
     * @param InviteSender $sender
     * @return Response
     */
    public function test(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find(30);
        $user->setRole(User::USER);
        $this->getDoctrine()->getManager()->flush();
        return new Response($encoder->encodePassword($this->getDoctrine()->getRepository(User::class)->find(30), "12345"));
    }

    /**
     * @Route("/api/users/repair2/{base}")
     * @param Request $request
     * @param $base
     * @param UserPasswordEncoderInterface $encoder
     * @param InviteSender $sender
     * @return Response
     */
    public function passfix2(Request $request, $base, UserPasswordEncoderInterface $encoder, InviteSender $sender)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findBy(["login"=>base64_decode($base)]);
        if(count($users) == 0)
            return new Response("Mail not found", 404);
        $user = $users[0];
        $password = User::generatePassword();
        $user->setPassword($encoder->encodePassword($user, $password));
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        $sender->sendPassword($user, $password);
        return new Response("The new password was sent to you by e-mail. Enjoy the Live!");
    }

    /**
     * @Route("/api/users/get")
     * @param Request $request
     * @return JsonResponse
     */
    public function getAll(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if($data == null)
            $data = [];
        $criteria = new Criteria();
        foreach($data as $k=>$v){
            if(!$v){
                unset($data[$k]);
            }else{
                $criteria->orWhere(Criteria::expr()->eq($k, $v));
            }
        }
        $users = $this->getDoctrine()->getRepository(User::class)->matching($criteria)->toArray();
        $builder = SerializerBuilder::create();
        $serializer = $builder->build();
        $context = SerializationContext::create()->enableMaxDepthChecks();
        $json = $serializer->serialize($users, 'json', $context);
        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/api/users/super/url/{num}")
     * @param $num
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function super($num, UserPasswordEncoderInterface $encoder, InviteSender $sender)
    {
        $us = [];
        $passwords = [];
        // Open the file for reading
        if (($h = fopen(__DIR__. "/datav2-".$num.".csv", "r")) !== FALSE)
        {
            $flag = true;
            // Convert each line into the local $data variable
            while (($data = fgetcsv($h, 1000, ",")) !== FALSE)
            {
//                if($flag){
//                    $flag = false;
//                    continue;
//                }
//                dump($data);
                $user = $this->getDoctrine()->getRepository(User::class)->findBy(["login"=>$data[3]])[0];
                $user->setCompany($data[0] ?? "");
                $user->setName(explode(" ", $data[1])[0] ?? "");
                $user->setSurname(explode(" ", $data[1])[1] ?? "");
                $user->setPosition($data[2] ?? "");
                $user->setLogin($data[3] ?? "");
                $password = User::generatePassword();
                $passwords[] = $password;
                $user->setPassword($encoder->encodePassword($user, $password));
                $user->setAir($data[4] ?? "");
                $user->setAirport($data[5] ?? "");
                $user->setTransport($data[6] ?? "");
                $user->setRole(User::USER);
                $user->setPhone("");
                $user->setAccess(0);
                $us[] = $user;
            }

            // Close the file
            fclose($h);
        }
        foreach($us as $user){
            $this->getDoctrine()->getManager()->persist($user);
            $sender->sendPassword($user, $password);
        }
        $this->getDoctrine()->getManager()->flush();
        foreach($us as $k => $user){
            $sender->sendPassword($user, $passwords[$k]);
        }
        $builder = SerializerBuilder::create();
        $serializer = $builder->build();
        $context = SerializationContext::create()->enableMaxDepthChecks();
        $json = $serializer->serialize($us, 'json', $context);
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
            return new Response("You don’t have an access. Contact please the organizer to get access.", 403);
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
        $creator = $this->getUser();
        foreach($creator->getCreatedMeets() as $meet){
            if($meet->getSlot()->getId() === $slot->getId()){
                return new Response("Вы уже создали встречу на это время", 418);
            }
        }
        if(count($creator->getCreatedMeets()) >= 2){
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
     * @Route("/api/users/{id}/recovery")
     * @param int $id
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @param InviteSender $sender
     * @return Response
     */
    public function password(int $id, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, InviteSender $sender)
    {
        $user = $em->getRepository(User::class)->find($id);
        if($user === null){
            return new Response("Not found", 404);
        }
        $password = User::generatePassword();
        $sender->sendPassword($user, $password);
        $user->setPassword($encoder->encodePassword($user, $password));
        $em->flush();
        $builder = SerializerBuilder::create();
        $serializer = $builder->build();
        $context = SerializationContext::create()->enableMaxDepthChecks();
        $json = $serializer->serialize($user, 'json', $context);
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
        if(isset($data["isAdmin"]) && $data["isAdmin"]){
            $user->setRole(User::ADMIN);
        }
        $user->setLogin($data['login']);
        $user->setName($data['name']);
        $user->setSurname($data['surname']);
        $user->setPhone($data['phone']);
        $user->setCompany($data['company']);
        $user->setPosition($data['position']);
        $user->setAccess($data['access']);
        $user->setAir($data['air']);
        $user->setAirport($data['airport']);
        $user->setTransport($data['transport']);
        return $data['login'] && $data['name'] && $data['surname'] &&
            $data['phone'] && $data['company'] && $data['position'];
    }
}
