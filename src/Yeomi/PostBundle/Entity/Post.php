<?php

namespace Yeomi\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="Yeomi\PostBundle\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Post
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
     * @ORM\ManyToMany(targetEntity="Yeomi\PostBundle\Entity\Category", inversedBy="posts")
     */
    private $categories;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Yeomi\PostBundle\Entity\Image", mappedBy="post", cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Yeomi\PostBundle\Entity\Comment", mappedBy="post", cascade={"remove"})
     */
    private $comments;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \Yeomi\PostBundle\Entity\Type
     *
     * @ORM\ManyToOne(targetEntity="Yeomi\PostBundle\Entity\Type", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean", nullable=true)
     */
    private $published;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setCreated(new \DateTime());
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
     * Set title
     *
     * @param string $title
     * @return Post
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
     * Set content
     *
     * @param string $content
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * Add categories
     *
     * @param \Yeomi\PostBundle\Entity\Category $categories
     * @return Post
     */
    public function addCategory(\Yeomi\PostBundle\Entity\Category $category)
    {
        $this->categories[] = $category;
        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Yeomi\PostBundle\Entity\Category $categories
     */
    public function removeCategory(\Yeomi\PostBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set categories
     * @param \Doctrine\Common\Collections\Collection $categories
     *
     * @return Post
     */
    public function setCategories($categories)
    {

        if(!is_array($categories))
        {
            $categories = array($categories);
        }
        $this->categories = $categories;

        return $this;
    }

    /**
     * Add images
     *
     * @param \Yeomi\PostBundle\Entity\Image $images
     * @return Post
     */
    public function addImage(\Yeomi\PostBundle\Entity\Image $images)
    {
        $this->images[] = $images;
        $images->setPost($this);

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
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
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
     * Set type
     *
     * @param \Yeomi\PostBundle\Entity\Type $type
     * @return Post
     */
    public function setType(\Yeomi\PostBundle\Entity\Type $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Yeomi\PostBundle\Entity\Type 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add comments
     *
     * @param \Yeomi\PostBundle\Entity\Comment $comments
     * @return Post
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
}
