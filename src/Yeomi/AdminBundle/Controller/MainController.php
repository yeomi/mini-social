<?php

namespace Yeomi\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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

    public function listcommentsAction()
    {
        $comments = $this->getDoctrine()->getRepository("YeomiPostBundle:Comment")->findAll();
        return $this->render('YeomiAdminBundle:Main:listComments.html.twig', array(
            "comments" => $comments,
        ));
    }

    public function deleteEntityAction($type, $id)
    {
        $authorized = array("comment, user, post");
        if(in_array($type, $authorized)) {
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

        return $this->redirect($this->generateUrl("yeomi_admin_list_" . $type . "s"));
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

        return $this->redirect($this->generateUrl("yeomi_admin_list_users"));
    }

}
