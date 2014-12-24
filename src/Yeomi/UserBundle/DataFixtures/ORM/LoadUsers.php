<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 03/12/2014
 * Time: 23:13
 */

namespace Yeomi\UserBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Yeomi\UserBundle\Entity\Role;
use Yeomi\UserBundle\Entity\User;

class LoadUsers implements FixtureInterface {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $users = array(
            'bindou' => array(
                'email' => 'bindou@bin.fr',
                'password' => '1234',
            ),
            'bumbo' => array(
                'email' => 'boumboum@bumb.fr',
                'password' => '1234',
            ),
            'damour' => array(
                'email' => 'damour@binbumb.fr',
                'password' => '1234',
            )
        );


        $roleA = new Role();
        $roleA->setName('Unvalidate User')
            ->setSlug('ROLE_UNVALIDATE');
        $roleB = new Role();
        $roleB->setName('Authenticated User')
            ->setSlug('ROLE_USER');
        $roleC = new Role();
        $roleC->setName('Premium User')
            ->setSlug('ROLE_USER_PREMIUM');

        foreach ($users as $username => $userData) {
            $user = new User();


            $user->setUsername($username)
                ->setEmail($userData["email"])
                ->setPassword($userData["password"])
                ->addRole($roleA)
                ->addRole($roleB);

            $manager->persist($roleC);
            $manager->persist($user);
        }

        $manager->flush();

    }
}