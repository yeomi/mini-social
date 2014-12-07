<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 03/12/2014
 * Time: 00:11
 */

namespace Yeomi\PostBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Yeomi\PostBundle\Form\PostType;
use Yeomi\PostBundle\Entity\Post;
use Yeomi\PostBundle\Entity\Image;

class MainController extends Controller
{


    public function indexAction()
    {
        return $this->render("YeomiPostBundle:Main:index.html.twig", array(

        ));
    }

    public function createAction(Request $request)
    {
        $post = new Post();
        $image = new Image();
        $post->addImage($image);

        $form = $this->createForm(new PostType(), $post);

        if($form->handleRequest($request)->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($post);
            $manager->flush();

            //return $this->redirect($this->generateUrl("yeomi_post_index"));
        }


        return $this->render("YeomiPostBundle:Main:create.html.twig", array(
            "form" => $form->createView(),
        ));
    }

}
