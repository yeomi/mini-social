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
use Yeomi\PostBundle\Entity\Category;
use Yeomi\PostBundle\Form\CategoryType;
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

    public function viewAction()
    {

        $posts = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findAll();

        return $this->render("YeomiPostBundle:Main:view.html.twig", array(
            "posts" => $posts
        ));
    }

    public function addPostAction(Request $request)
    {
        $post = new Post();

        $imgAllow = 3;
        $images = array();

        for ($i = 0; $i<$imgAllow; $i++) {
            $images[$i] = new Image();
            $post->addImage($images[$i]);
        }

        $form = $this->createForm(new PostType(), $post);

        if($form->handleRequest($request)->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($post);
            $manager->flush();

            return $this->redirect($this->generateUrl("yeomi_post_index"));
        }


        return $this->render("YeomiPostBundle:Main:addPost.html.twig", array(
            "form" => $form->createView(),
        ));
    }



    public function addCategoryAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(new CategoryType(), $category);

        if($form->handleRequest($request)->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();

            //return $this->redirect($this->generateUrl("yeomi_post_index"));
        }


        return $this->render("YeomiPostBundle:Main:addPost.html.twig", array(
            "form" => $form->createView(),
        ));
    }

    public function editAction(Request $request, $id)
    {
        $post = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->find($id);

        $form = $this->createForm(new PostType(), $post);

        if($form->handleRequest($request)->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            //return $this->redirect($this->generateUrl("yeomi_post_index"));
        }


        return $this->render("YeomiPostBundle:Main:addPost.html.twig", array(
            "form" => $form->createView(),
        ));
    }

    public function deleteAction(Request $request, $id)
    {
        $post = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->find($id);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($post);
        $manager->flush();

        return $this->redirect($this->generateUrl("yeomi_post_view"));
    }

    public function resetPasswordAction(Request $request)
    {
     
        return $this->redirect($this->generateUrl("yeomi_post_view"));
    }
}
