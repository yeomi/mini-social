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
        $authorized = array("comment", "user", "post", "category", "newsletter_subscription");
        if(!in_array($type, $authorized)) {
            return $this->redirect($this->generateUrl("yeomi_admin_home"));
        }

        $ucType = ucfirst($type);

        $manager = $this->getDoctrine()->getManager();

        if ($type == 'newsletter_subscription') {
            $entity = $manager->getRepository("YeomiAppBundle:NewsletterSubscription")->find($id);
        } else if($type == "user") {
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

    // @todo not used, only used one time.
    public function sendActivationEmail(User $user)
    {

    }
    public function generateValidationToken($username, $password)
    {
        return hash("sha1", $username . $password);
    }
}
