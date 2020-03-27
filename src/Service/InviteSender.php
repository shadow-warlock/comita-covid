<?php


namespace App\Service;


use App\Entity\Meet;
use App\Entity\User;

class InviteSender {
    private $mailer;
    private $from = "atoevents@atoevents.ru";

    public function __construct(\Swift_Mailer $mailer) {
        $this->mailer = $mailer;
    }

    public function sendPassword(User $user, string $password) {
        $message = new \Swift_Message("Ваш пароль на сервисе встреч");
        $message->setFrom($this->from)
            ->setTo($user->getLogin())
            ->setBody( "Ваш пароль: " . $password, 'text/html');
        $this->mailer->send($message);
    }

    public function sendInvite(Meet $meet) {
        $message = new \Swift_Message("Вы приглашены на встречу");
        $message->setFrom($this->from)
            ->setTo($meet->getGuest()->getLogin())
            ->setBody( $this->makeText($meet), 'text/html');
        $this->mailer->send($message);
    }

    public function sendInviteResponse(Meet $meet) {
        $message = new \Swift_Message("Вы получили ответ на ваше приглашение о встрече");
        $message->setFrom($this->from)
            ->setTo($meet->getCreator()->getLogin())
            ->setBody( $this->makeTextResponse($meet), 'text/html');
        $this->mailer->send($message);
    }

    private function makeText(Meet $meet){
        return "Сегодня в " . $meet->getSlot()->getTime() . ".
        Отправитель " . $meet->getCreator()->getName() . " " . $meet->getCreator()->getSurname() .
            "<br><a href='//".$_ENV['DOMAIN']."/api/invite/".$meet->getId()."/confirm'>Принять</a><br>" .
            "<a href='//".$_ENV['DOMAIN']."/api/invite/".$meet->getId()."/fail'>Отклонить</a>";
    }

    private function makeTextResponse(Meet $meet){
        return "Ваше приглашение о встрече в " . $meet->getSlot()->getTime() . ".
        было " . $meet->getStatus() === Meet::CONFIRM ? "принято. " : "отклонено. Собеседник " . $meet->getCreator()->getName() . " " . $meet->getCreator()->getSurname();
    }

//    private function renderHtmlMessage(Message $mes) {
//        try {
//            return $this->renderer->render(
//                'security/message/message.html.twig',
//                ['message' => $mes->getText(), "header" => "На устройстве " . $mes->getEvent()->getDevice()->getName() . " произошло событие \"" . $mes->getEvent()->getTemplate()->getName() . "\"."]
//            );
//        } catch(Exception $e) {
//            return "Не удалось отрендерить html сообщение: " . $mes->getText() . " На устройстве " . $mes->getEvent()->getDevice()->getName() . " произошло событие \"" . $mes->getEvent()->getTemplate()->getName() . "\".";
//        }
//    }
}