<?php

namespace Yeomi\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="Yeomi\PostBundle\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Comment
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
     * @ORM\OneToMany(targetEntity="Yeomi\PostBundle\Entity\Image", mappedBy="comment", cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @var \Yeomi\PostBundle\Entity\Post
     *
     * @ORM\ManyToOne(targetEntity="Yeomi\PostBundle\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $post;

    /**
     * @var \Yeomi\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Yeomi\UserBundle\Entity\User", inversedBy="comments")
     */
    private $user;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Yeomi\PostBundle\Entity\Vote", mappedBy="comment")
     */
    private $votes;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published;


    public function __construct()
    {
        $this->setCreated(new \DateTime());
        $this->setPublished(true);
        $this->images = new ArrayCollection();
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
     * @return Comment
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
     * Set title
     *
     * @param string $title
     * @return Comment
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Comment
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
     * Set published
     *
     * @param boolean $published
     * @return Comment
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set post
     *
     * @param \Yeomi\PostBundle\Entity\Post $post
     * @return Comment
     */
    public function setPost(\Yeomi\PostBundle\Entity\Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \Yeomi\PostBundle\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Add images
     *
     * @param \Yeomi\PostBundle\Entity\Image $images
     * @return Comment
     */
    public function addImage(\Yeomi\PostBundle\Entity\Image $images)
    {
        $this->images[] = $images;
        $images->setComment($this);

        return $this;
    }

    /**
     * Remove images
     *
     * @param \Yeomi\PostBundle\Entity\Image $images
     */
    public function removeImage(\Yeomi\PostBundle\Entity\Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function deleteEmptyImage() {

        foreach ($this->getImages() as $image) {
            if($image->getFile()) {
            } else {
                $this->removeImage($image);
            }
        }
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set user
     *
     * @param \Yeomi\UserBundle\Entity\User $user
     * @return Comment
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

    /**
     * Add votes
     *
     * @param \Yeomi\PostBundle\Entity\Vote $votes
     * @return Comment
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
}
