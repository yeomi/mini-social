<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 23/01/2015
 * Time: 17:46
 */

namespace Yeomi\UserBundle\UserLogin;


use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Yeomi\UserBundle\Entity\User;

class Log
{

    /** @var EntityManager  */
    protected $manager;
    /** @var Session  */
    protected $session;
    /** @var  User */
    protected $user;

    public function __construct(EntityManager $entityManager, Session $session)
    {
        $this->manager = $entityManager;
        $this->session = $session;
    }


    public function outputNotification()
    {
        $username = $this->user->getDisplayUsername();
        $this->session->getFlashBag()->add("info", "Bienvenue $username !");

        if($this->user->checkRoleExist("ROLE_UNVALIDATE")) {
            $this->session->getFlashBag()->add("alert", "N'oubliez pas que pour profiter pleinement du site, vous devez valider votre compte en suivant les instructions reçu par e-mail !");
        }
    }

    public function rememberLogin(User $user)
    {
        $this->user = $user;
        $user->setLastLogin(new \DateTime());
        $this->manager->flush();

        $this->outputNotification();
    }
} 