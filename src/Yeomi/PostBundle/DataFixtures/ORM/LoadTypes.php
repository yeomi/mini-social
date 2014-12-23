<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 23/12/2014
 * Time: 12:46
 */

namespace Yeomi\PostBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Yeomi\PostBundle\Entity\Type;

class LoadTypes implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param Doctrine\Common\Persistence\ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $types = array(
            "defi" => "Défi",
            "histoire" => "Histoire"
        );

        foreach ($types as $slug => $name) {

            $type = new Type();
            $type->setName($name);
            $type->setSlug($slug);
            $type->setDescription("");
            $manager->persist($type);
        }

        $manager->flush();


    }
}