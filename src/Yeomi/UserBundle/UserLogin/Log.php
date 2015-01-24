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
        $username = $this->user->getUsername();
        $this->session->getFlashBag()->add("info", "Bienvenue $username !");
    }

    public function rememberLogin(User $user)
    {
        $this->user = $user;
        $user->setLastLogin(new \DateTime());
        $this->manager->flush();

        $this->outputNotification();
    }
} 