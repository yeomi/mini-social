<?php

namespace Yeomi\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Yeomi\AppBundle\Entity\NewsletterSubscription;
use Yeomi\AppBundle\Form\NewsletterSubscriptionType;
use Yeomi\UserBundle\Entity\User;

class AppController extends Controller
{
    
    public function newsletterViewAllAdminAction() {
        $newsletterSubscriptions = $this->getDoctrine()
            ->getRepository('YeomiAppBundle:NewsletterSubscription')
            ->findAll();

        return $this->render('@YeomiAdmin/Main/listNewsletterSubscriptions.html.twig', array(
            'newsletterSubscriptions' => $newsletterSubscriptions,
        ));
    }
    public function newsletterSubscribeAction(Request $request) {

        /** @var User $user */
        $user = $this->getUser();
        $userEmail = null;
        $em = $this->getDoctrine()->getEntityManager();
        $newsletterRepository = $em->getRepository('YeomiAppBundle:NewsletterSubscription');
        if (!is_null($user)) {
            $userEmail = $user->getEmail();
            $existing = $newsletterRepository->findOneBy(array('user' => $user->getId()));
            if ($existing) {
                return $this->render('YeomiAppBundle:Newsletter:form.html.twig', array(
                    'form' => null,
                    'existing' => $existing,
                ));
            }
        }




        $newsletterSubscription = new NewsletterSubscription();
        if (!is_null($user)) {
            $newsletterSubscription->setUser($user);
        }

        $options = array(
            'email' => $userEmail,
            'action' => $this->generateUrl('yeomi_app_newsletter_subscription')
        );

        $form = $this->createForm(new NewsletterSubscriptionType(), $newsletterSubscription, $options);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $email = $form->get('email')->getData();
            $existing = $newsletterRepository->findOneBy(array('email' => $email));
            if (!is_null($existing)) {
                $request->getSession()->getFlashBag()->add("error", "'Il y à déjà une inscription à la newsletter pour cette adresse e-mail.");
                $form->addError(new FormError('Il y à déjà une inscription à la newsletter pour cette adresse e-mail.'));
            }

            if ($form->isValid()) {
                $request->getSession()->getFlashBag()->add("info", "Vous êtes inscrit à la newsletter");
                $em->persist($newsletterSubscription);
                $em->flush();
            }

            return $this->redirect($this->generateUrl('yeomi_post_index'));

        }



        return $this->render('YeomiAppBundle:Newsletter:form.html.twig', array(
            'form' => $form->createView(),
            'existing' => null,
        ));
    }

    public function newsletterUnsubscribeAction(Request $request) {

        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getEntityManager();

            $email = $request->request->get('_email');
            $existing = $em->getRepository('YeomiAppBundle:NewsletterSubscription')
                ->findOneBy(array('email' => $email));

            if ($existing) {
                $em->remove($existing);
                $em->flush();
                $request->getSession()->getFlashBag()->add("info", "Vous êtes désinscrit de la newsletter");
            } else {
                $request->getSession()->getFlashBag()->add("alert", "L'email indiqué ne fait pas partie des inscrits à la newsletter");
            }

        }

        return $this->render('@YeomiApp/Newsletter/unsubscribe.html.twig', array(

        ));

    }
}
