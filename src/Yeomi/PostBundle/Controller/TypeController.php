<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 06/01/2015
 * Time: 19:34
 */

namespace Yeomi\PostBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TypeController extends Controller{

    public function viewAction($slug)
    {
        $type = $this->getDoctrine()->getRepository("YeomiPostBundle:Type")->findOneBy(array("slug" => $slug));

        return $this->render("YeomiPostBundle:Type:view.html.twig", array(
            "type" => $type
        ));
    }

    public function listTypeAction(Request $request, $type, $limit = 3, $offset = 0)
    {
        $posts = $this->getDoctrine()->getRepository("YeomiPostBundle:Post")->findWithOffsetLimit($limit, $offset);

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

} 