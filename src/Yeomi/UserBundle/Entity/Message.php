<?php

namespace Yeomi\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="Yeomi\UserBundle\Repository\MessageRepository")
 */
class Message
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
     * @var \Yeomi\UserBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="Yeomi\UserBundle\Entity\User", inversedBy="messagesReceived")
     * @ORM\JoinColumn(name="recipient_id")
     */
    private $recipient;

    /**
     * @var string
     *
     * @var \Yeomi\UserBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="Yeomi\UserBundle\Entity\User", inversedBy="messagesSent")
     * @ORM\JoinColumn(name="sender_id")
     */
    private $sender;

    /**
     * @var String
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank(message="Vous ne pouvez pas envoyer un message vide")
     * @Assert\Length(
     *      max = "500",
     *      maxMessage = "Votre texte ne doit pas dépasser {{ limit }} caractères"
     * )
     */
    private $content;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $isRead;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


    public function __construct()
    {
        $this->setCreated(new \DateTime());
        $this->setIsRead(false);
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
     * Set content
     *
     * @param string $content
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Message
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
     * Set recipient
     *
     * @param \Yeomi\UserBundle\Entity\User $recipient
     * @return Message
     */
    public function setRecipient(\Yeomi\UserBundle\Entity\User $recipient = null)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * Get recipient
     *
     * @return \Yeomi\UserBundle\Entity\User 
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Set sender
     *
     * @param \Yeomi\UserBundle\Entity\User $sender
     * @return Message
     */
    public function setSender(\Yeomi\UserBundle\Entity\User $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \Yeomi\UserBundle\Entity\User 
     */
    public function getSender()
    {
        return $this->sender;
    }



    /**
     * Set isRead
     *
     * @param boolean $isRead
     * @return Message
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean 
     */
    public function getIsRead()
    {
        return $this->isRead;
    }
}
