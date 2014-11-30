<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 01/12/2014
 * Time: 00:37
 */

namespace Yeomi\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller {

  public function testAction()
  {
    return $this->render("YeomiUserBundle:Main:test.html.twig", array(
      "testVar" => 12,
    ));
  }

} 