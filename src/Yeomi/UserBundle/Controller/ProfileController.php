<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 09/01/2015
 * Time: 12:22
 */

namespace Yeomi\UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    public function viewAction($username)
    {
        $user = $this
            ->getDoctrine()
            ->getRepository("YeomiUserBundle:User")
            ->findOneBy(array("username" => $username));

        return $this->render("YeomiUserBundle:Profile:view.html.twig", array(
            "user" => $user,
        ));
    }

} 