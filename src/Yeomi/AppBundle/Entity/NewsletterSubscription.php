<?php

namespace Yeomi\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NewsletterSubscription
 *
 * @ORM\Table(name="newsletter_subscription")
 * @ORM\Entity
 */
class NewsletterSubscription
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;


    /**
     * @var \Yeomi\UserBundle\Entity\User
     *
     * @ORM\OneToOne(targetEntity="Yeomi\UserBundle\Entity\User")
     */
    private $user;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return NewsletterSubscription
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set user
     *
     * @param \Yeomi\UserBundle\Entity\User $user
     *
     * @return NewsletterSubscription
     */
    public function setUser(\Yeomi\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Yeomi\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
