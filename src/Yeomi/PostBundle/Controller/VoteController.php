<?php


namespace Yeomi\PostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yeomi\PostBundle\Entity\Vote;

class VoteController extends Controller
{
    public function voteAction($entityType, $entityId, $type)
    {

        if (!in_array($entityType, array("post", "comment"))) {
            throw new NotFoundHttpException("This entity type is not allowed");
        }

        $ucEntityType = ucfirst($entityType);
        $entity = $this->getDoctrine()->getRepository("YeomiPostBundle:" . $ucEntityType)->find($entityId);

        if (is_null($entity)) {
            throw new NotFoundHttpException("Entity not found");
        }

        $vote = new Vote();

        $vote->{"set" . $ucEntityType}($entity);
        $vote->setUser($this->getUser());
        if($type == "like") {
            $vote->setPositive(true);
        } else {
            $vote->setNegative(true);
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($vote);
        $manager->flush();

        return new Response("passe");
    }

} 