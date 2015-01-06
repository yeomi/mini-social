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

    public function findByTypeSlug($type, $limit, $offset, $categoryId = null)
    {
        $qb = $this->createQueryBuilder("p")
            ->innerJoin("p.type", "t")
            ->where("t.slug = :type")
            ->setParameter("type", $type)
            ->leftJoin("p.comments", "c")
            ->leftJoin("p.images", "img")
            ->leftJoin("p.user", "user")
            ->leftJoin("p.votes", "votes")
            ->innerJoin("p.categories", "cat")
            ->addSelect("c")
            ->addSelect("img")
            ->addSelect("user")
            ->addSelect("votes")
            ->addSelect("cat")
            ->orderBy("p.created", "DESC")
            ->setFirstResult($offset)
            ->setMaxResults($limit);
        if(!is_null($categoryId)) {
            $qb->andWhere("cat.id = :categoryId")
                ->setParameter("categoryId", $categoryId);
        }
        $query = $qb->getQuery();

        return new Paginator($query, true);

    }


    public function findMostRecents($limit, $offset)
    {
        $query = $this->createQueryBuilder("p")
            ->leftJoin("p.comments", "c")
            ->leftJoin("p.images", "img")
            ->leftJoin("p.user", "user")
            ->leftJoin("p.votes", "votes")
            ->innerJoin("p.categories", "cat")
            ->addSelect("c")
            ->addSelect("img")
            ->addSelect("user")
            ->addSelect("votes")
            ->addSelect("cat")
            ->orderBy("p.lastAction", "DESC")
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        return new Paginator($query, true);

    }

    public function findPopularPost($limit, $offset)
    {

        $query = $this->createQueryBuilder("p")
            ->leftJoin("p.comments", "c")
            ->leftJoin("p.images", "img")
            ->leftJoin("p.user", "user")
            ->leftJoin("p.votes", "v")
            ->innerJoin("p.categories", "cat")
            ->addSelect("(COUNT(DISTINCT v.id)) + (COUNT(DISTINCT c.id) * 2) as pop")
            ->addSelect("img")
            ->addSelect("user")
            ->addSelect("cat")
            ->groupBy("p.id")
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy("pop", "DESC")
            ->getQuery();

        /* problem when we addSelect votes and comments, because of the group by, only one result is selected) */

        /* For now we take even negative vote as popularity points,
           This needs to be improved to take only positive with the following :
//            ->addSelect("SUM(case when votes.positive=1 THEN 1 ELSE 0 END) as nbvotes")

            ->addSelect("(COUNT(DISTINCT case v.id)) + (COUNT(DISTINCT c.id) * 2) as pop")
        */

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
