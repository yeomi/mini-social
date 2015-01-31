<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 06/01/2015
 * Time: 19:34
 */

namespace Yeomi\PostBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Yeomi\PostBundle\Entity\Category;
use Yeomi\PostBundle\Form\CategoryType;

class CategoryController extends Controller{

    public function viewAction($slug)
    {
        $category = $this->getDoctrine()->getRepository("YeomiPostBundle:Category")->findOneBy(array("slug" => $slug));

        return $this->render("YeomiPostBundle:Category:view.html.twig", array(
            "category" => $category
        ));
    }

    public function listCategoryAction($categoryId, $type, $limit = 3, $offset = 0)
    {
        $posts = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findByTypeSlug($type, $limit, $offset, $categoryId);

        return $this->render("YeomiPostBundle:Main:list.html.twig", array(
            "posts" => $posts,
        ));
    }

    public function addCategoryAction(Request $request, $id = null)
    {
        if($id) {
            $category = $this->getDoctrine()->getRepository("YeomiPostBundle:Category")->find($id);
        } else {
            $category = new Category();
        }

        $form = $this->createForm(new CategoryType(), $category);

        if($form->handleRequest($request)->isValid()) {

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();

            return $this->redirect($this->generateUrl("yeomi_admin_list_categories"));
        }


        return $this->render("YeomiPostBundle:Category:addCategory.html.twig", array(
            "form" => $form->createView(),
        ));
    }

} 