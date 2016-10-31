<?php

namespace Yeomi\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Yeomi\UserBundle\Entity\User;

class MainController extends Controller
{
    public function indexAction()
    {

        return $this->render('YeomiAdminBundle:Main:index.html.twig');
    }

    public function listUsersAction()
    {
        $users = $this->getDoctrine()->getRepository("YeomiUserBundle:User")->findAll();
        return $this->render('YeomiAdminBundle:Main:listUsers.html.twig', array(
            "users" => $users,
        ));
    }

    public function listPostsAction()
    {
        $posts = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findAll();
        return $this->render('YeomiAdminBundle:Main:listPosts.html.twig', array(
            "posts" => $posts,
        ));
    }

    public function listCategoriesAction()
    {
        $categories = $this->getDoctrine()->getRepository("YeomiPostBundle:Category")->findAll();
        return $this->render('YeomiAdminBundle:Main:listCategories.html.twig', array(
            "categories" => $categories,
        ));
    }

    public function listcommentsAction()
    {
        $comments = $this->getDoctrine()->getRepository("YeomiPostBundle:Comment")->findAll();
        return $this->render('YeomiAdminBundle:Main:listComments.html.twig', array(
            "comments" => $comments,
        ));
    }

    public function deleteEntityAction($type, $id)
    {
        $authorized = array("comment", "user", "post", "category");
        if(!in_array($type, $authorized)) {
            return $this->redirect($this->generateUrl("yeomi_admin_home"));
        }

        $ucType = ucfirst($type);

        $manager = $this->getDoctrine()->getManager();

        if($type == "user") {
            $entity = $manager->getRepository("YeomiUserBundle:User")->find($id);
            if($entity->checkRoleExist("ROLE_ADMIN")) {
                return $this->redirect($this->generateUrl("yeomi_admin_home"));
            }
        } else {
            $entity = $manager->getRepository("YeomiPostBundle:$ucType")->find($id);
        }

        $manager->remove($entity);
        $manager->flush();

        return $this->redirect($this->generateUrl("yeomi_admin_list_" . $type));
    }

    public function blockUserAction($id, $unblock = 0)
    {

        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("YeomiUserBundle:User")->find($id);
        if($user->checkRoleExist("ROLE_ADMIN")) {
            return $this->redirect($this->generateUrl("yeomi_admin_home"));
        }

        if ($unblock == 1) {
            $user->setStatus(0);
        } else {
            $user->setStatus(-1);
        }

        $manager->flush();

        return $this->redirect($this->generateUrl("yeomi_admin_list_user"));
    }

    public function activateUsersAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $users = $manager->getRepository("YeomiUserBundle:User")->activateMass('03/06/2015');
        $role = $manager->getRepository("YeomiUserBundle:Role")->findOneBy(array("slug" => "ROLE_USER"));

        if(count($users) == 0) {
            return $this->render("YeomiAdminBundle:Main:actionDone.html.twig", array(
                "content" => "<h1>Tous les utilisateurs ont été prevenus !</h1>",
            ));
        }

        foreach($users as $user) {
            $user->setPasswordOutdated(true);
            $user->removeRoleBySlug("ROLE_UNVALIDATE");
            $user->addRole($role);
            $this->sendActivationEmail($user);
        }
        $manager->flush();

        return $this->render("YeomiAdminBundle:Main:actionDone.html.twig", array(
           "content" => "<h1>L'action à bien été effectuée</h1>",
        ));
    }

    public function sendActivationEmail(User $user)
    {
        $body = "Bonjour " . $user->getDisplayUsername() . " !\n"
            . "Nous avons le plaisir de vous annoncer la mise en ligne de la nouvelle version du site Tpaschiche.com.\n"
            . "En 2014 vous étiez membre, si vous désirez continuer à profiter pleinement du site vous devez cliquer sur le lien ci-dessous pour recevoir les nouveaux accès à votre compte : \n"
            . $this->generateUrl(
                "yeomi_reset_password_validate",
                array(
                    "token" => $this->generateValidationToken($user->getDisplayUsername(), $user->getPassword()),
                    "id" => $user->getId(),
                ),
                UrlGeneratorInterface::ABSOLUTE_URL
            )
            . "\n"
            . "\n"
            . "Petit rappel, Tpaschiche.com c'est quoi ?\n\n"
            . "Un site pour partager vos défis et histoires en toute liberté...\n\n"
            . "Dernières histoires postées par la communauté :\n\n"
            . "\" Je me souviens d'une petite journée à St Tropez, nous étions dans une rue un peu retirée quand un mec commence à dire \"Obama, Obama vient vite...\", alors je me dis quoi le Président des States derrière moi ? Je me retourne et je vois un petit chihuahua courir vers son maître ! \" - 04/03/15 à 9h20 anonyme - Animaux\n\n"
            . "\" Je vis avec mon lapin dans un studio et je lui donne du foin, mais il sent très très fort et ça pourrait vite faire penser à du camping dans un champ, oui oui croyez moi! La dernière fois que j'ai reçu des amis chez moi, on commençait à prendre l'apéro, quand une pote me demande : \"Tu mets quoi comme parfum? Il sent fort je trouve...\" \" -  14/03/15 à 22h53 marcf - Animaux\n\n"
            . "\" Ce matin après avoir fait l'amour mon copain me dit qu'avec moi c'est top car j'y rappelle quelqu'un. Je lui demande alors qui, il me dit \"tu ne connais pas c'est une actrice porno\" - 15/03/15 à 14h37 anonyme - Amour\n\n"
            . "\nLa suite ? A vous de l'écrire !\n\n"
            . "\nBon divertissement sur Tpaschiche.com";

        $message = \Swift_Message::newInstance()
            ->setSubject("Info sur votre compte membre Tpaschiche.com")
            ->setFrom("contact@tpaschiche.com")
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
}
