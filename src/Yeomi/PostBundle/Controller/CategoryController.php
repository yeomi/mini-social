<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 06/01/2015
 * Time: 19:34
 */

namespace Yeomi\PostBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller{

    public function viewAction($id)
    {
        $category = $this->getDoctrine()->getRepository("YeomiPostBundle:Category")->find($id);

        return $this->render("YeomiPostBundle:Category:view.html.twig", array(
            "category" => $category
        ));
    }

    public function listCategoryAction($, $type, $limit = 3, $offset = 0)
    {
        $posts = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findByTypeSlug($type, $limit, $offset, $categoryId);

        return $this->render("YeomiPostBundle:Main:list.html.twig", array(
            "posts" => $posts,
        ));

    }

} 