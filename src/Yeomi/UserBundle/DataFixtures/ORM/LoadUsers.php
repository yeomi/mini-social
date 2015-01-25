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
            'user1' => array(
                'email' => 'user1@hotmail.fr',
                'password' => '123456',
            ),
            'user2' => array(
                'email' => 'user2@hotmail.fr',
                'password' => '123456',
            ),
            'user3' => array(
                'email' => 'user3@hotmail.fr',
                'password' => '123456',
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
        $roleC = new Role();
        $roleC->setName('Administrator')
            ->setSlug('ROLE_ADMIN');

        foreach ($users as $username => $userData) {
            $user = new User();


            $user->setUsername($username)
                ->setEmail($userData["email"])
                ->setPassword($userData["password"])
                ->addRole($roleB);

            $manager->persist($roleC);
            $manager->persist($user);
        }

        $admin = new User();
        $admin->setUsername("admin")
            ->setEmail("admin@hotmail.fr")
            ->setPassword("123456")
            ->addRole($roleC);

        $notValidate = new User();
        $notValidate->setUsername("unvalidate")
            ->setEmail("unvalidate@hotmail.fr")
            ->setPassword("123456")
            ->addRole($roleA);

        $manager->persist($admin);
        $manager->persist($notValidate);

        $manager->flush();

    }
}