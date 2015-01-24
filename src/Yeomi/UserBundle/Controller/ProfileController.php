<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 09/01/2015
 * Time: 12:22
 */

namespace Yeomi\UserBundle\Controller;


use Proxies\__CG__\Yeomi\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yeomi\UserBundle\Entity\Message;
use Yeomi\UserBundle\Entity\Profile;
use Yeomi\UserBundle\Form\MessageType;
use Yeomi\UserBundle\Form\ProfileType;

class ProfileController extends Controller
{
    public function viewAction(Request $request, $username)
    {
        $tabMessage = false;
        if ($request->query->get("tab")) {
            $tabMessage = true;
        }

        $user = $this
            ->getDoctrine()
            ->getRepository("YeomiUserBundle:User")
            ->findOneBy(array("username" => $username));

        return $this->render("YeomiUserBundle:Profile:view.html.twig", array(
            "user" => $user,
            "tabMessage" => $tabMessage
        ));
    }

    public function createProfileAction(Request $request, $userId)
    {

        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("YeomiUserBundle:User")->find($userId);
        $profile = is_null($user->getProfile()) ? new Profile(): $user->getProfile();
        $isUpdated = false;

        if($this->isCurrentUser($user)) {
            $form = $this->createForm(new ProfileType(), $profile);

            $user->setProfile($profile);

            if ($form->handleRequest($request)->isValid()) {
                $manager->persist($profile);
                $manager->flush();
                $isUpdated = true;
            }


            return $this->render("YeomiUserBundle:Profile:createProfile.html.twig", array(
                "form" => $form->createView(),
                "userId" => $userId,
                "isUpdated" => $isUpdated
            ));
        } else {
            return $this->render("YeomiUserBundle:Profile:viewProfile.html.twig", array(
                "profile" => $profile,
                "userId" => $userId,
            ));
        }

    }

    public function listActivityAction($userId)
    {
        $user = $this->getDoctrine()->getRepository("YeomiUserBundle:User")->find($userId);

        $posts = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findBy(array("user" => $user));

        return $this->render("YeomiUserBundle:Profile:listActivity.html.twig", array(
            "posts" => $posts,
        ));
    }

    public function isCurrentUser(\Yeomi\UserBundle\Entity\User $user)
    {
        $currentUser = $this->getUser();

        if (is_null($currentUser)) {
            return false;
        }

        if ($currentUser->getId() == $user->getId()) {
            return true;
        } else {
            return false;
        }
    }

    public function createMessageAction(Request $request, $userId)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository("YeomiUserBundle:User")->find($userId);

        $isUser = $this->isCurrentUser($user);
        $message = new Message();
        $isSent = false;
        $form = $this->createForm(new MessageType(), $message);

        if ($form->handleRequest($request)->isValid()) {

            $message->setRecipient($user);
            $message->setSender($this->getUser());

            $manager->persist($message);
            $manager->flush();
            $isSent = true;
        }

        $messages = null;
        if($isUser) {
            $messages = $manager->getRepository("YeomiUserBundle:Message")->findby(array("recipient" => $user), array("id" => "DESC"));
            $messageUnread = array();
            foreach($messages as $message) {
                $messageUnread []= clone $message;
                $message->setIsRead(true);
            }
            $manager->flush();

            $messages = $messageUnread;
        }


        return $this->render("YeomiUserBundle:Profile:createMessage.html.twig", array(
            "form" => $form->createView(),
            "isUser" => $isUser,
            'isSent' => $isSent,
            "messages" => $messages,
            "userId" => $userId,
        ));
    }

    public function checkUnreadMessageAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $messages = $manager->getRepository("YeomiUserBundle:Message")->findby(array("recipient" => $user, "isRead" => false));

        return new Response(count($messages));
    }

} 