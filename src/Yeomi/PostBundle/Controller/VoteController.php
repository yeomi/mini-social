<?php


namespace Yeomi\PostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yeomi\PostBundle\Entity\Vote;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

class VoteController extends Controller
{


    public function voteAction(Request $request, $entityType, $entityId, $type)
    {

        if(!$request->isXmlHttpRequest()) {
            return new Response("");
        }
        $response = new Response();


        $cookieNewEntry = false;
        $cookieDeleteEntry = false;


        if (!in_array($entityType, array("post", "comment"))) {
            throw new NotFoundHttpException("This entity type is not allowed");
        }
        $manager = $this->getDoctrine()->getManager();
        $ucEntityType = ucfirst($entityType);
        $entity = $manager->getRepository("YeomiPostBundle:" . $ucEntityType)->find($entityId);

        if (is_null($entity)) {
            throw new NotFoundHttpException("Entity not found");
        }


        $voteRepository = $manager
            ->getRepository("YeomiPostBundle:Vote");

        $user = $this->getUser();

        if(is_null($user)) {

            $jsonCookie = $request->cookies->get("SESSIONSTPC");
            $cookie = json_decode($jsonCookie, true);

            if(is_null($jsonCookie)) {
                $cookie = array("post" => array(), "comment" => array());
            }

            if(isset($cookie[$entityType][$entityId])) {
                $cookieDeleteEntry = true;
                unset($cookie[$entityType][$entityId]);
            } else {
                $cookieNewEntry = true;
                $cookie[$entityType][$entityId] = $type == "positive" ? 1: 0;
            }

            $jsonCookie = json_encode($cookie);

            $response->headers->setCookie(new Cookie("SESSIONSTPC", $jsonCookie));

            if($cookieDeleteEntry) {
                $existing = $voteRepository->findOneBy(array(
                    "user" => null, $entityType => $entity, $type => 1
                ));
                $manager->remove($existing);
                $manager->flush();

                $response->setContent("remove");
                return $response;
            }

            $vote = new Vote();

            $vote->{"set" . $ucEntityType}($entity);
            $vote->setUser($user);
            $vote->{"set" . $type}(true);

            $manager->persist($vote);
            $manager->flush();
            $response->setContent("add");


        } else {

            $existing = $voteRepository->findOneBy(array(
                "user" => $user, $entityType => $entity
            ));

            if(!is_null($existing)) {
                $manager->remove($existing);
                $manager->flush();

                $response->setContent("remove");
                return $response;
            }

            $vote = new Vote();

            $vote->{"set" . $ucEntityType}($entity);
            $vote->setUser($user);
            $vote->{"set" . $type}(true);

            $manager->persist($vote);
            $manager->flush();

            $response->setContent("add");

        }

        return $response;
    }

} 