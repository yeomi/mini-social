<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 01/12/2014
 * Time: 00:37
 */

namespace Yeomi\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

class MainController extends Controller {

  public function testAction()
  {
    return $this->render("YeomiUserBundle:Main:test.html.twig", array(
      "testVar" => 12,
    ));
  }

  public function loginAction(Request $request)
  {

    if($this->get("security.context")->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
      return $this->redirect($this->generateUrl("yeomi_user_test"));
    }

    $session = $request->getSession();

    if($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
      $error = $request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
    } else {
      $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
      $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
    }

    return $this->render("YeomiUserBundle:Main:login.html.twig", array(
      "last_username" => $session->get(SecurityContextInterface::LAST_USERNAME),
      "error" => $error,
    ));
  }

} 