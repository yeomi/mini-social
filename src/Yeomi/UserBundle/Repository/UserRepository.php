<?php

namespace Yeomi\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Yeomi\UserBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository implements UserProviderInterface
{

    public function search($search)
    {
        $query = $this->createQueryBuilder("u")
            ->where("u.username LIKE :search")
            ->setParameter(":search", "%$search%")
            ->setMaxResults(15)
            ->orderBy("u.id", "DESC")
            ->getQuery();
        return $query->getResult();
    }


    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     *
     */
    public function loadUserByUsername($username)
    {
        $query = $this->createQueryBuilder("u")
            //->leftJoin("u.roles", "r")
            ->where("u.username = :username")
            ->orWhere("u.email = :username")
            ->having("u.status = 0")
            //->andWhere("r.slug = :role")
            ->setParameter(":username", $username)
            //->setParameter(":role", "ROLE_USER")
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Whether this provider supports the given user class
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'Yeomi\UserBundle\User';
    }

    public function activateMass($fromDate = null)
    {
        $query = $this->createQueryBuilder("u")
        ->leftJoin("u.roles", "r")
        ->having("u.status = 0")
        ->andWhere("r.slug = :role")
        ->setParameter(":role", "ROLE_UNVALIDATE");


        if(!is_null($fromDate)) {
            $query->andWhere('u.created <= :date')
                ->setParameter(':date', \DateTime::createFromFormat('m/d/Y', $fromDate));
        }

        $query->setMaxResults(3)
            ->getQuery();


        return new Paginator($query, true);
    }

}
