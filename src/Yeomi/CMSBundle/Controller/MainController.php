<?php

namespace Yeomi\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Yeomi\CMSBundle\Entity\Page;
use Yeomi\CMSBundle\Form\PageType;

class MainController extends Controller
{
    public function testAction()
    {
        return $this->render('YeomiCMSBundle:Main:test.html.twig');
    }
    public function createPageAction(Request $request)
    {
        $page = new Page();

        $form = $this->createForm(new PageType(), $page);

        if($form->handleRequest($request)->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($page);
            $manager->flush();
        }

        return $this->render('YeomiCMSBundle:Main:createPage.html.twig', array(
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
}
