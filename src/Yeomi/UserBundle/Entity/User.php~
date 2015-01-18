<?php

namespace Yeomi\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table("user")
 * @ORM\Entity(repositoryClass="Yeomi\UserBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Yeomi\UserBundle\Entity\Role", cascade={"persist"})
     */
    private $roles;

    /**
     * @var \Yeomi\UserBundle\Entity\Profile
     *
     * @ORM\OneToOne(targetEntity="Yeomi\UserBundle\Entity\Profile", inversedBy="user", cascade={"persist"})
     */
    private $profile;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Yeomi\PostBundle\Entity\Post", mappedBy="user")
     */
    private $posts;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Yeomi\PostBundle\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Yeomi\PostBundle\Entity\Vote", mappedBy="user")
     */
    private $votes;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Yeomi\UserBundle\Entity\Message", mappedBy="recipient")
     */
    private $messagesReceived;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Yeomi\UserBundle\Entity\Message", mappedBy="sender")
     */
    private $messagesSent;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="password_outdated", type="boolean")
     */
    private $passwordOutdated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->setCreated(new \DateTime());
        $this->setPasswordOutdated(false);
        $this->setStatus(0);
    }
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = hash("sha512", $password);

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return NULL;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
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
     * Set created
     *
     * @param \DateTime $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime 
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Add roles
     *
     * @param \Yeomi\UserBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\Yeomi\UserBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Yeomi\UserBundle\Entity\Role $roles
     */
    public function removeRole(\Yeomi\UserBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Remove roles by slug
     *
     * @param string
     */
    public function removeRoleBySlug($slug)
    {
        foreach ($this->roles as $role) {
            if ($role->getSlug() == $slug) {
                $this->roles->removeElement($role);
            }
        }
    }

    /**
     * Get roles
     *
     * @return Array
     */
    public function getRoles()
    {
        $roles = array();

        foreach ($this->roles as $role) {
            $roles []= $role->getSlug();
        }
        return $roles;
    }

    public function checkRoleExist($slug) {

        $roles = $this->getRoles();

        return in_array($slug, $roles);
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set passwordOutdated
     *
     * @param boolean $passwordOutdated
     * @return User
     */
    public function setPasswordOutdated($passwordOutdated)
    {
        $this->passwordOutdated = $passwordOutdated;

        return $this;
    }

    /**
     * Get passwordOutdated
     *
     * @return boolean 
     */
    public function getPasswordOutdated()
    {
        return $this->passwordOutdated;
    }

    /**
     * Add posts
     *
     * @param \Yeomi\PostBundle\Entity\Post $posts
     * @return User
     */
    public function addPost(\Yeomi\PostBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Yeomi\PostBundle\Entity\Post $posts
     */
    public function removePost(\Yeomi\PostBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Add comments
     *
     * @param \Yeomi\PostBundle\Entity\Comment $comments
     * @return User
     */
    public function addComment(\Yeomi\PostBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Yeomi\PostBundle\Entity\Comment $comments
     */
    public function removeComment(\Yeomi\PostBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add votes
     *
     * @param \Yeomi\PostBundle\Entity\Vote $votes
     * @return User
     */
    public function addVote(\Yeomi\PostBundle\Entity\Vote $votes)
    {
        $this->votes[] = $votes;

        return $this;
    }

    /**
     * Remove votes
     *
     * @param \Yeomi\PostBundle\Entity\Vote $votes
     */
    public function removeVote(\Yeomi\PostBundle\Entity\Vote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Set profile
     *
     * @param \Yeomi\UserBundle\Entity\Profile $profile
     * @return User
     */
    public function setProfile(\Yeomi\UserBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Yeomi\UserBundle\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Add messagesReceived
     *
     * @param \Yeomi\UserBundle\Entity\Message $messagesReceived
     * @return User
     */
    public function addMessagesReceived(\Yeomi\UserBundle\Entity\Message $messagesReceived)
    {
        $this->messagesReceived[] = $messagesReceived;

        return $this;
    }

    /**
     * Remove messagesReceived
     *
     * @param \Yeomi\UserBundle\Entity\Message $messagesReceived
     */
    public function removeMessagesReceived(\Yeomi\UserBundle\Entity\Message $messagesReceived)
    {
        $this->messagesReceived->removeElement($messagesReceived);
    }

    /**
     * Get messagesReceived
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessagesReceived()
    {
        return $this->messagesReceived;
    }

    /**
     * Add messagesSent
     *
     * @param \Yeomi\UserBundle\Entity\Message $messagesSent
     * @return User
     */
    public function addMessagesSent(\Yeomi\UserBundle\Entity\Message $messagesSent)
    {
        $this->messagesSent[] = $messagesSent;

        return $this;
    }

    /**
     * Remove messagesSent
     *
     * @param \Yeomi\UserBundle\Entity\Message $messagesSent
     */
    public function removeMessagesSent(\Yeomi\UserBundle\Entity\Message $messagesSent)
    {
        $this->messagesSent->removeElement($messagesSent);
    }

    /**
     * Get messagesSent
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessagesSent()
    {
        return $this->messagesSent;
    }
}
