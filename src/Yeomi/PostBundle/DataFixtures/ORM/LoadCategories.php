<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 03/12/2014
 * Time: 23:13
 */

namespace Yeomi\PostBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Yeomi\PostBundle\Entity\Category;
use Yeomi\UserBundle\Entity\Role;
use Yeomi\UserBundle\Entity\User;

class LoadCategories implements FixtureInterface {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $categories = array(
            "funny" => "Funny",
            "love-story" => "Love Story",
            "work-offer" => "Work offer",
        );
        $desc = "Lorem ipsum ..";

        foreach ($categories as $slug => $name) {
            $category = new Category();


            $category->setName($name)->setSlug($slug)->setDescription($desc);

            $manager->persist($category);
        }

        $manager->flush();

    }
}