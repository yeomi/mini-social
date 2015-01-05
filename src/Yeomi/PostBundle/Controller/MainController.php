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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yeomi\PostBundle\Entity\Category;
use Yeomi\PostBundle\Entity\Comment;
use Yeomi\PostBundle\Form\CategoryType;
use Yeomi\PostBundle\Form\CommentType;
use Yeomi\PostBundle\Form\PostType;
use Yeomi\PostBundle\Entity\Post;
use Yeomi\PostBundle\Entity\Image;
use Symfony\Component\HttpFoundation\Response;
use Yeomi\PostBundle\Entity\Vote;

class MainController extends Controller
{


    public function indexAction()
    {
        $defis = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findByTypeSlug("histoire", 2, 0);

        return $this->render("YeomiPostBundle:Main:index.html.twig", array(
            "defis" => $defis,
        ));
    }


    public function listAction($type, $limit = 3, $offset = 0)
    {
        $posts = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findByTypeSlug($type, $limit, $offset);

        return $this->render("YeomiPostBundle:Main:list.html.twig", array(
            "posts" => $posts,
        ));

    }

    public function testAction($limit = 3, $offset = 0)
    {
        $posts = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findPopularPost($limit, $offset);

        return $this->render("YeomiPostBundle:Main:test.html.twig", array(
            "posts" => $posts,
        ));
    }

    public function listPopularAction($limit = 3, $offset = 0)
    {
        $posts = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findPopularPost($limit, $offset);

        return $this->render("YeomiPostBundle:Main:list.html.twig", array(
            "posts" => $posts,
        ));
    }

    public function viewAction()
    {

        $posts = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findAll();

        return $this->render("YeomiPostBundle:Main:view.html.twig", array(
            "posts" => $posts
        ));
    }

    public function viewFullAction($id)
    {

        $post = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->getPostComplete($id);
        return $this->render("YeomiPostBundle:Main:viewFull.html.twig", array(
            "post" => $post
        ));
    }


    public function addPostAction(Request $request, $type)
    {
        $post = new Post();
        $type = $this->getDoctrine()->getRepository("YeomiPostBundle:Type")->findOneBy(array("slug" => $type));
        $user = $this->getUser();

        if(!is_null($user)) {
            $post->setUser($user);
        }

        if(is_null($type)) {
            throw New NotFoundHttpException("Cette page n'existe pas, veuillez vérifier l'url");
        }

        $post->setType($type);

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


    public function addCommentAction (Request $request, $id)
    {

        if (!$this->get("security.context")->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            return new Response("You need to have an account ...");
        }

        $user = $this->getUser();
        $comment = new Comment();

        $comment->setUser($user);

        $imgAllow = 3;
        $images = array();

        for ($i = 0; $i<$imgAllow; $i++) {
            $images[$i] = new Image();
            $comment->addImage($images[$i]);
        }

        $post = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->find($id);

        $comment->setPost($post);

        $form = $this->createForm(new CommentType(), $comment);

        if ($form->handleRequest($request)->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($comment);
            $manager->flush();
        }

        return $this->render("YeomiPostBundle:Main:addComment.html.twig", array(
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

}
