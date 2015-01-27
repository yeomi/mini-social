<?php

namespace Yeomi\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Yeomi\CMSBundle\Entity\Article;
use Yeomi\CMSBundle\Entity\Page;
use Yeomi\CMSBundle\Form\ArticleType;
use Yeomi\CMSBundle\Form\PageType;

class MainController extends Controller
{
    public function testAction()
    {
        return $this->render('YeomiCMSBundle:Main:test.html.twig');
    }
    public function createPageAction(Request $request, $id = null)
    {
        $page = new Page();

        if($id) {
            $page = $this->getDoctrine()->getRepository("YeomiCMSBundle:Page")->find($id);
        }

        $form = $this->createForm(new PageType(), $page);

        if($form->handleRequest($request)->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($page);
            $manager->flush();
            return $this->redirect($this->generateUrl("yeomi_cms_list_content", array("contentType" => "page")));
        }

        return $this->render('YeomiCMSBundle:Main:createPage.html.twig', array(
            "form" => $form->createView()
        ));
    }

    public function createArticleAction(Request $request, $id = null)
    {
        $article = new Article();

        if($id) {
            $article = $this->getDoctrine()->getRepository("YeomiCMSBundle:Article")->find($id);
        }
        $form = $this->createForm(new ArticleType(), $article);

        if($form->handleRequest($request)->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($article);
            $manager->flush();
            return $this->redirect($this->generateUrl("yeomi_cms_list_content", array("contentType" => "article")));
        }

        return $this->render('YeomiCMSBundle:Main:createArticle.html.twig', array(
            "form" => $form->createView()
        ));
    }

    public function getListOfPageAction()
    {
        $pages = $this->getDoctrine()->getRepository("YeomiCMSBundle:Page")->findAll();

        return $this->render('YeomiCMSBundle:Main:listOfPage.html.twig', array(
            "pages" => $pages,
        ));
    }

    public function viewPageAction($id)
    {
        $page = $this->getDoctrine()->getRepository("YeomiCMSBundle:Page")->find($id);

        return $this->render('YeomiCMSBundle:Main:viewPage.html.twig', array(
            "page" => $page,
        ));
    }

    public function viewArticleAction($id)
    {
        $article = $this->getDoctrine()->getRepository("YeomiCMSBundle:Article")->find($id);

        return $this->render('YeomiCMSBundle:Main:viewArticle.html.twig', array(
            "article" => $article,
        ));
    }

    public function listArticleAction()
    {
        $articles = $this->getDoctrine()->getRepository("YeomiCMSBundle:Article")->findAll();

        return $this->render('YeomiCMSBundle:Main:listArticle.html.twig', array(
            "articles" => $articles,
        ));
    }

    public function listContentAction($contentType)
    {
        $ucType = ucfirst($contentType);
        $contents = $this->getDoctrine()->getRepository("YeomiCMSBundle:" . $ucType)->findAll();

        return $this->render('YeomiCMSBundle:Main:listContent.html.twig', array(
            "type" => $contentType,
            "contents" => $contents
        ));
    }

    public function deleteContentAction($contentType, $id)
    {
        $ucType = ucfirst($contentType);
        $content = $this->getDoctrine()->getRepository("YeomiCMSBundle:" . $ucType)->find($id);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($content);
        $manager->flush();

        return $this->redirect($this->generateUrl("yeomi_cms_list_content", array("contentType" => $contentType)));
    }
}
