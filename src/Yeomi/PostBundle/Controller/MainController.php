<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 03/12/2014
 * Time: 00:11
 */

namespace Yeomi\PostBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    public function testAction(Request $request, $limit = 3, $offset = 0)
    {
        if ($request->isMethod("POST")) {
            var_dump($request->request->get("search"));
            $search = $request->request->get("search");
            $resultsPost = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->search($search);
            $resultsUser = $this->getDoctrine()->getRepository("YeomiUserBundle:User")->search($search);

            var_dump($resultsPost);
            var_dump($resultsUser);

        }

        return $this->render("YeomiPostBundle:Main:test.html.twig", array(

        ));
    }

    public function indexAction()
    {
        $defis = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findByTypeSlug("histoire", 2, 0);

        $articles = $this->getDoctrine()->getRepository("YeomiCMSBundle:Article")->findFive();

        return $this->render("YeomiPostBundle:Main:index.html.twig", array(
            "defis" => $defis,
            "articles" => $articles,
        ));
    }

    public function menuAction()
    {
        $categories = $this->getDoctrine()->getRepository("YeomiPostBundle:Category")->findAll();

        return $this->render("YeomiPostBundle:Elements:menu.html.twig", array(
            "categories" => $categories,
        ));

    }

    public function listAction(Request $request, $type, $limit = 3, $offset = 0)
    {
        $posts = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findByTypeSlug($type, $limit, $offset);

        if($request->isXmlHttpRequest()) {

            if(count($posts) - $limit - $offset > 0) {
                $keepGoing = true;
            } else {
                $keepGoing = false;
            }

            $jsonResponse = array($this->renderView("YeomiPostBundle:Main:list.html.twig", array(
                "posts" => $posts,
            )), $keepGoing);

            return new JsonResponse($jsonResponse);
        }

        return $this->render("YeomiPostBundle:Main:list.html.twig", array(
            "posts" => $posts,
        ));

    }

    public function listCommentAction($postId, $limit = NULL, $offset = 0)
    {
        $comments = $this->getDoctrine()->getRepository("YeomiPostBundle:Comment")->getCommentsComplete($postId);

        return $this->render("YeomiPostBundle:Main:listComment.html.twig", array(
            "comments" => $comments,
        ));

    }


    public function listPopularAction(Request $request, $limit = 3, $offset = 0)
    {
        $posts = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findPopularPost($limit, $offset);

        $cleanArrayPosts = array();
        foreach($posts as $post)
        {
            $cleanArrayPosts []= $post[0];
        }

        if($request->isXmlHttpRequest()) {

            if(count($posts) - $limit - $offset > 0) {
                $keepGoing = true;
            } else {
                $keepGoing = false;
            }

            $jsonResponse = array($this->renderView("YeomiPostBundle:Main:list.html.twig", array(
                "posts" => $cleanArrayPosts,
            )), $keepGoing);

            return new JsonResponse($jsonResponse);
        }

        return $this->render("YeomiPostBundle:Main:list.html.twig", array(
            "posts" => $cleanArrayPosts,
        ));
    }

    public function listMostRecentAction(Request $request, $limit = 3, $offset = 0)
    {
        $posts = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findMostRecents($limit, $offset);

        if($request->isXmlHttpRequest()) {

            if(count($posts) - $limit - $offset > 0) {
                $keepGoing = true;
            } else {
                $keepGoing = false;
            }

            $jsonResponse = array($this->renderView("YeomiPostBundle:Main:list.html.twig", array(
                "posts" => $posts,
            )), $keepGoing);

            return new JsonResponse($jsonResponse);
        }

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

    public function viewFullAction($type, $id)
    {
        $post = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->getPostComplete($id);

        $prevPost = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->getSiblingPost("<", $post->getType()->getId(), $post->getId());
        $nextPost = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->getSiblingPost(">", $post->getType()->getId(), $post->getId());

        $prevPostId = is_null($prevPost) ? null : $prevPost->getId();
        $nextPostId = is_null($nextPost) ? null : $nextPost->getId();

        return $this->render("YeomiPostBundle:Main:viewFull.html.twig", array(
            "post" => $post,
            "prevPostId" => $prevPostId,
            "nextPostId" => $nextPostId,
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
        $form = $form->handleRequest($request);
        if($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($post);
            $manager->flush();

            return $this->redirect($this->generateUrl("yeomi_post_index"));
        } elseif ($form->getErrors()->count() > 0) {
            foreach ($form->getErrors() as $error) {
                $request->getSession()->getFlashBag()->add("alert", $error->getMessage());
            }
            return $this->redirect($this->generateUrl("yeomi_post_index"));
        }

        $template = $request->getPathInfo() == "/_fragment" ? "YeomiPostBundle:Main:addPostBlock.html.twig" : "YeomiPostBundle:Main:addPost.html.twig";

        return $this->render($template, array(
            "form" => $form->createView(),
            "type" => $type,
        ));
    }


    public function addCommentAction (Request $request, $postId)
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

        $post = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->find($postId);

        $comment->setPost($post);

        $form = $this->createForm(new CommentType(), $comment);
        $form = $form->handleRequest($request);
        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($comment);
            $manager->flush();
            return $this->redirect($this->generateUrl("yeomi_post_view_full", array("type" => $post->getType()->getSlug(), "id" => $postId)));
        } elseif ($form->getErrors()->count() > 0) {
            foreach ($form->getErrors() as $error) {
                $request->getSession()->getFlashBag()->add("alert", $error->getMessage());
            }
            return $this->redirect($this->generateUrl("yeomi_post_view_full", array("type" => $post->getType()->getSlug(), "id" => $postId)));
        }

        return $this->render("YeomiPostBundle:Main:addComment.html.twig", array(
           "form" => $form->createView(),
            "type" => $post->getType()->getSlug(),
            "postId" => $postId,
        ));
    }

    public function editAction(Request $request, $id)
    {
        $post = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->find($id);

        $form = $this->createForm(new PostType(), $post);

        if($form->handleRequest($request)->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            return $this->redirect($this->generateUrl("yeomi_post_index"));
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

    public function searchAction(Request $request)
    {
        if ($request->isMethod("POST")) {
            $search = $request->request->get("site_search");
            $posts = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->search($search);
            return $this->render("YeomiPostBundle:Main:search.html.twig", array(
                "posts" => $posts,
                "searchWord" => $search,
            ));
        }

        return $this->redirect($this->generateUrl("yeomi_post_index"));

    }


}
