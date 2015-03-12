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
use Yeomi\UserBundle\Form\UserEditType;
use Yeomi\UserBundle\Form\UserPasswordType;
use Yeomi\UserBundle\Form\UserType;

class ProfileController extends Controller
{
    public function viewAction(Request $request, $username)
    {
        $user = $this
            ->getDoctrine()
            ->getRepository("YeomiUserBundle:User")
            ->findOneBy(array("username" => $username));

        $isCurrentUser = $this->isCurrentUser($user);
        $tabMessage = false;
        $tabParams = false;
        if ($request->query->get("tab") == "message") {
            $tabMessage = true;
        } elseif($request->query->get("tab") == "params"
        && $isCurrentUser) {
            $tabParams = true;
        }


        return $this->render("YeomiUserBundle:Profile:view.html.twig", array(
            "user" => $user,
            "tabMessage" => $tabMessage,
            "tabParams" => $tabParams,
            "isCurrentUser" => $isCurrentUser,
        ));
    }

    public function viewProfileAction(Request $request, $userId)
    {

        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("YeomiUserBundle:User")->find($userId);
        $profile = is_null($user->getProfile()) ? new Profile(): $user->getProfile();
        $isUpdated = false;
        $formView = false;

        if($this->isCurrentUser($user)) {
            $form = $this->createForm(new ProfileType(), $profile);

            $user->setProfile($profile);

            if ($form->handleRequest($request)->isValid()) {
                $manager->persist($profile);
                $manager->flush();
                $isUpdated = true;
            }

            $formView = $form->createView();

        }

        return $this->render("YeomiUserBundle:Profile:viewProfile.html.twig", array(
            "profile" => $profile,
            "userId" => $userId,
            "form" => $formView,
            "isUpdated" => $isUpdated
        ));

    }

    public function viewParameterAction(Request $request)
    {
        $user = $this->getUser();
        // This need to be done or token will be updated even if validator block operation
        $lastUsername = $this->getDoctrine()->getRepository("YeomiUserBundle:User")->find($user->getId())->getUsername();
        $formUser = $this->createForm(new UserEditType(), $user);
        $formPassword = $this->createForm(new UserPasswordType(), $user);

        if ($formUser->handleRequest($request)->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            $request->getSession()->getFlashBag()->add("info", "Vos paramètre de compte ont bien été mis à jour");
            return $this->redirect($this->generateUrl("yeomi_user_profile", array("username" => $user->getUsername())) . "?tab=params");
        } elseif ($formUser->getErrors()->count() > 0) {
            $user->setUsername($lastUsername);
            foreach ($formUser->getErrors() as $error) {
                $request->getSession()->getFlashBag()->add("alert", $error->getMessage());
            }
            return $this->redirect($this->generateUrl("yeomi_user_profile", array("username" => $user->getUsername())) . "?tab=params");
        }

        if ($formPassword->handleRequest($request)->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            $request->getSession()->getFlashBag()->add("info", "Vos paramètre de compte ont bien été mis à jour");
            return $this->redirect($this->generateUrl("yeomi_user_profile", array("username" => $user->getUsername())) . "?tab=params");
        } elseif ($formPassword->getErrors()->count() > 0) {
            foreach ($formPassword->getErrors() as $error) {
                $request->getSession()->getFlashBag()->add("alert", $error->getMessage());
            }
            return $this->redirect($this->generateUrl("yeomi_user_profile", array("username" => $user->getUsername())) . "?tab=params");
        }


        return $this->render("YeomiUserBundle:Profile:viewParameter.html.twig", array(
            "formUser" => $formUser->createView(),
            "formPassword" => $formPassword->createView()
        ));

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

    public function createMessageAction(Request $request, $userId, $isSent = false)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository("YeomiUserBundle:User")->find($userId);

        $isUser = $this->isCurrentUser($user);
        $message = new Message();
        $form = $this->createForm(new MessageType(), $message);

        if ($form->handleRequest($request)->isValid()) {

            $message->setRecipient($user);
            $message->setSender($this->getUser());

            $manager->persist($message);
            $manager->flush();
            return $this->redirect($this->generateUrl("yeomi_user_create_message", array("userId" =>  $userId, "isSent" => true)));
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