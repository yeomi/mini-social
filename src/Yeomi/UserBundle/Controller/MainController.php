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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Yeomi\UserBundle\Entity\User;
use Yeomi\UserBundle\Form\UserType;

class MainController extends Controller
{

    public function testAction()
    {
        if ($this->get("security.context")->isGranted("ROLE_UNVALIDATE")) {
            return new Response("Send me again a confirmation link");
        }

        return $this->render("YeomiUserBundle:Main:test.html.twig", array(
            "testVar" => 12,
        ));
    }

    public function loginAction(Request $request)
    {

        if ($this->get("security.context")->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            return $this->redirect($this->generateUrl("yeomi_user_test"));
        }

        $session = $request->getSession();

        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        }

        return $this->render("YeomiUserBundle:Main:login.html.twig", array(
            "last_username" => $session->get(SecurityContextInterface::LAST_USERNAME),
            "error" => $error,
        ));
    }

    public function sendEmailValidation(User $user)
    {
        $body = "Bonjour " . $user->getUsername() . "\n"
            . "Nous avions bien reçu ta demande d'inscription,\n"
            . "Pour la valider clique sur ce lien : \n"
            . $this->generateUrl(
                "yeomi_user_validate",
                array(
                    "token" => $this->generateValidationToken($user->getUsername(), $user->getPassword()),
                    "id" => $user->getId(),
                ),
                UrlGeneratorInterface::ABSOLUTE_URL
            );

        $message = \Swift_Message::newInstance()
            ->setSubject("Hello")
            ->setFrom("contact.yeomi@gmail.com")
            //->setTo($user->getEmail())
            ->setTo("gabriel@henao.fr")
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
        $user = new User();

        $form = $this->createForm(new UserType(), $user);

        if ($form->handleRequest($request)->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $role = $manager->getRepository("YeomiUserBundle:Role")->findOneBy(array("slug" => "ROLE_UNVALIDATE"));

            if(is_null($role)) {
                throw new EntityNotFoundException;
            }

            $user->addRole($role);
            $manager->persist($user);
            $manager->flush();

            // User is not confirmed until he does not confirm mail
            $this->sendEmailValidation($user);

        }

        return $this->render("YeomiUserBundle:Main:register.html.twig", array(
            "form" => $form->createView(),
        ));
    }

    public function validateAction($id, $token)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("YeomiUserBundle:User")->find($id);

        $validationToken = $this->generateValidationToken($user->getUsername(), $user->getPassword());

        if($validationToken == $token) {

            if($user->getStatus() == 1) {
                return new Response("Ce compte à déjà été validé");
            }

            $user->setStatus(1);
            $manager->flush();
            return new Response("Felicitations, vous pouvez à présent vous connecter et commencer à profiter du site !");
        }

        return new Response("Erreur d'authentification");
    }
}