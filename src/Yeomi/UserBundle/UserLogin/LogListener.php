<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 23/01/2015
 * Time: 18:03
 */

namespace Yeomi\UserBundle\UserLogin;

use Symfony\Component\Security\Core\Event\AuthenticationEvent;

class LogListener
{

    protected $log;

    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function processLog(AuthenticationEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $this->log->rememberLogin($user);
    }

} 