<?php

namespace Yeomi\PostBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{

    public function findByTypeSlug($type, $limit, $offset)
    {
        $query = $this->createQueryBuilder("p")
            ->innerJoin("p.type", "t")
            ->where("t.slug = :type")
            ->setParameter("type", $type)
            ->leftJoin("p.images", "img")
            ->leftJoin("p.user", "user")
            ->leftJoin("p.votes", "votes")
            ->innerJoin("p.categories", "cat")
            ->addSelect("img")
            ->addSelect("user")
            ->addSelect("votes")
            ->addSelect("cat")
            ->orderBy("p.created", "DESC")
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query, true);

    }

    public function getPostComplete($id)
    {
        $query = $this->createQueryBuilder("p")
            ->where("p.id = :id")
            ->setParameter("id", $id)
            ->leftJoin("p.images", "img")
            ->leftJoin("p.user", "user")
            ->leftJoin("p.comments", "com")
            ->leftJoin("p.votes", "votes")
            ->leftJoin("com.images", "imgcom")
            ->innerJoin("p.categories", "cat")
            ->addSelect("img")
            ->addSelect("user")
            ->addSelect("cat")
            ->addSelect("com")
            ->addSelect("imgcom")
            ->addSelect("votes")
            ->getQuery();


        return $query->getOneOrNullResult();
    }
}
