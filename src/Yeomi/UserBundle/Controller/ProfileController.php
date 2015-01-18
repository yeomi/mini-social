<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 09/01/2015
 * Time: 12:22
 */

namespace Yeomi\UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Yeomi\UserBundle\Entity\Profile;
use Yeomi\UserBundle\Form\ProfileType;

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

    public function createProfileAction(Request $request, $userId)
    {

        $manager = $this->getDoctrine()->getManager();
        $user = $manager->getRepository("YeomiUserBundle:User")->find($userId);
        $profile = is_null($user->getProfile()) ? new Profile(): $user->getProfile();
        $form = $this->createForm(new ProfileType(), $profile);

        $user->setProfile($profile);

        if ($form->handleRequest($request)->isValid()) {

            $manager->persist($profile);
            $manager->flush();
        }

        return $this->render("YeomiUserBundle:Profile:createProfile.html.twig", array(
            "form" => $form->createView(),
            "userId" => $userId,
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

} 