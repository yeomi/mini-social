<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 01/12/2014
 * Time: 00:37
 */

namespace Yeomi\UserBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Serializer\Serializer;
use Yeomi\AppBundle\Entity\NewsletterSubscription;
use Yeomi\UserBundle\Entity\User;
use Yeomi\UserBundle\Form\UserType;

class MainController extends Controller
{

    public function testAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $users = $manager->getRepository("YeomiUserBundle:User")->findAll();

       $array = array(1,2,3);

        return new JsonResponse($array);
        //return new Response(json_encode($usersArray));
    }

    public function loginAction(Request $request)
    {
        if ($this->get("security.context")->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
        }

        $session = $request->getSession();

        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        }

        $template = $request->getPathInfo() == "/_fragment" ? "YeomiUserBundle:Main:loginBlock.html.twig" : "YeomiUserBundle:Main:login.html.twig";

        return $this->render($template, array(
            "last_username" => $session->get(SecurityContextInterface::AUTHENTICATION_ERROR),
            "error" => $error,
        ));
    }

    public function userPanelAction(Request $request, $unreadMessages = 0)
    {

        return $this->render("YeomiUserBundle:Main:userPanel.html.twig", array(
            "unreadMessages" => $unreadMessages,
        ));
    }

    public function sendEmailValidation(User $user)
    {
        $body = "Hello " . $user->getDisplayUsername() . " !,\n"
            . "Nous avons bien reçu votre demande d'inscription,\n"
            . "Pour confirmer votre adresse email et valider votre compte YooTips, cliquez sur ce lien : \n"
            . $this->generateUrl(
                "yeomi_user_validate",
                array(
                    "token" => $this->generateValidationToken($user->getDisplayUsername(), $user->getPassword()),
                    "id" => $user->getId(),
                ),
                UrlGeneratorInterface::ABSOLUTE_URL
            )
            . "\n\Vous pourrez publier de nouveaux commentaires, participer à tous les votes, envoyer un message privé à un membre, etc...\n"
            . "À tout de suite sur http://www.yootips.com"
        ;

        $message = \Swift_Message::newInstance()
            ->setSubject("Yootips : plus qu'une étape pour valider votre compte !")
            ->setFrom("contact@yootips.com")
            ->setTo($user->getEmail())
            //->setTo("gabriel@henao.fr")
            ->setBody($body);
        ;

        $this->get("mailer")->send($message);
    }

    public function sendResetPasswordValidation(User $user)
    {
        $body = "Hello " . $user->getDisplayUsername() . " !\n"
            . "Vous avez fait une demande pour régénérer votre mot de passe,\n"
            . "Pour cela, il vous suffit de cliquer ici : \n"
            . $this->generateUrl(
                "yeomi_reset_password_validate",
                array(
                    "token" => $this->generateValidationToken($user->getDisplayUsername(), $user->getPassword()),
                    "id" => $user->getId(),
                ),
                UrlGeneratorInterface::ABSOLUTE_URL
            )
            . "\n\nVos identifiants de connexion vont être envoyés dans un prochain mail...\n"
            . "À tout de suite...";

        $message = \Swift_Message::newInstance()
            ->setSubject("Yootips : vous avez demandé un nouveau mot de passe ?")
            ->setFrom("contact@yootips.com")
            ->setTo($user->getEmail())
            //->setTo("gabriel@henao.fr")
            ->setBody($body);
        ;

        $this->get("mailer")->send($message);
    }

    public function generateValidationToken($username, $password)
    {
        return hash("sha1", $username . $password);
    }

    public function registerAction(Request $request)
    {
        if ($this->get("security.context")->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            return $this->redirect($this->generateUrl("yeomi_post_index"));
        }

        $user = new User();

        $form = $this->createForm(new UserType(), $user);

        if ($form->handleRequest($request)->isValid()) {

            $manager = $this->getDoctrine()->getEntityManager();
            $role = $manager->getRepository("YeomiUserBundle:Role")->findOneBy(array("slug" => "ROLE_UNVALIDATE"));


            $subscribeNewsletter = false;
            if ($form->get('newsletter')->getData()) {
                $email = $user->getEmail();
                $existing = $manager->getRepository('YeomiAppBundle:NewsletterSubscription')->findOneBy(array('email' => $email));
                if (is_null($existing)) {
                    $subscribeNewsletter = true;
                    $newsletterSubscription = new NewsletterSubscription();
                    $newsletterSubscription->setUser($user);
                    $newsletterSubscription->setEmail($email);
                }
            }

            if(is_null($role)) {
                throw new EntityNotFoundException;
            }

            $user->addRole($role);
            $manager->persist($user);
            if ($subscribeNewsletter) {
                $manager->persist($newsletterSubscription);
            }
            $manager->flush();

            // User is not confirmed until he does not confirm mail
            $this->sendEmailValidation($user);

            return $this->redirect($this->generateUrl("yeomi_user_register_success"));

        }

        return $this->render("YeomiUserBundle:Main:register.html.twig", array(
            "form" => $form->createView(),
        ));
    }
    public function registrationCompleteAction()
    {
        if ($this->get("security.context")->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            return $this->redirect($this->generateUrl("yeomi_post_index"));
        }
        return $this->render("YeomiUserBundle:Main:registrationComplete.html.twig");
    }

    public function validateAction($id, $token)
    {
        if ($this->get("security.context")->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            return $this->redirect($this->generateUrl("yeomi_post_index"));
        }
        
        $typeOfAction = 3;

        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("YeomiUserBundle:User")->find($id);

        $validationToken = $this->generateValidationToken($user->getDisplayUsername(), $user->getPassword());

        if($validationToken == $token) {

            if($user->checkRoleExist("ROLE_USER")) {
                $typeOfAction = 2;
            } else {
                $typeOfAction = 1;
                $role = $manager->getRepository("YeomiUserBundle:Role")->findOneBy(array("slug" => "ROLE_USER"));
                $user->removeRoleBySlug("ROLE_UNVALIDATE");
                $user->addRole($role);
                $manager->flush();
            }
        }
        return $this->render("YeomiUserBundle:Main:validate.html.twig", array(
            "typeOfAction" => $typeOfAction,
        ));
    }

    public function resetPasswordAction(Request $request)
    {
        if ($this->get("security.context")->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            return $this->redirect($this->generateUrl("yeomi_post_index"));
        }

        $isDone = false;
        if ($request->isMethod("POST")) {

            if (!filter_var($request->request->get("email_given"), FILTER_VALIDATE_EMAIL)) {
                $request->getSession()->getFlashBag()->add("error", "L'adresse e-mail n'est pas valide");
                return $this->redirect($this->generateUrl("yeomi_user_password_reset"));
            }

            $email = $request->request->get("email_given");
            $user = $this->getDoctrine()->getRepository("YeomiUserBundle:User")->findOneBy(array("email" => $email));

            if (is_null($user)) {
                $request->getSession()->getFlashBag()->add("error", "Cette adresse e-mail n'est associée à aucun compte");
                return $this->redirect($this->generateUrl("yeomi_user_password_reset"));
            }

            $manager = $this->getDoctrine()->getManager();

            $this->sendResetPasswordValidation($user);

            $user->setPasswordOutdated(true);
            $manager->flush();
            $isDone = true;
        }
        return $this->render("YeomiUserBundle:Main:resetPassword.html.twig", array(
            "isDone" => $isDone,
        ));
    }

    public function resetPasswordValidateAction($id, $token)
    {
        if ($this->get("security.context")->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            return $this->redirect($this->generateUrl("yeomi_post_index"));
        }

        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("YeomiUserBundle:User")->find($id);

        $validationToken = $this->generateValidationToken($user->getDisplayUsername(), $user->getPassword());

        if (!$user->getPasswordOutdated()) {
            $isOutdated = false;
        } else {
            $isOutdated = true;

            if ($validationToken == $token) {
                $oldPassword = $user->getPassword();
                $newPassword = hash("crc32", $oldPassword);
                $user->setPassword($newPassword);
                $user->setPasswordOutdated(false);
                $manager->flush();

                $body = "Bien joué !\n"
                    . "Ci-dessous vos identifiants de connexion... :\n\n"
                    . " - Pseudo : " . $user->getDisplayUsername() . "\n"
                    . " - Nouveau mot de passe : " . $newPassword . "\n\n"
                    . "Nous vous conseillons de personnaliser votre mot de passe à partir de votre profil... \n\n"
                    . "À tout de suite sur http://www.yootips.com";

                $message = \Swift_Message::newInstance()
                    ->setSubject("Yootips : Vos identifiants de connexion")
                    ->setFrom("contact@yootips.com")
                    ->setTo($user->getEmail())
                    //->setTo("gabriel@henao.fr")
                    ->setBody($body);;

                $this->get("mailer")->send($message);
            }
        }

        return $this->render("YeomiUserBundle:Main:resetPasswordValidate.html.twig", array(
            "isOutdated" => $isOutdated,
        ));
    }

}