<?php

namespace Yeomi\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     * @Assert\NotBlank(message="Vous devez choisir une catégorie")
     */
    private $categories;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Yeomi\PostBundle\Entity\Image", mappedBy="post", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $images;

    /**
     * @var \Yeomi\PostBundle\Entity\Video
     *
     * @ORM\OneToOne(targetEntity="Yeomi\PostBundle\Entity\Video", inversedBy="post", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $video;
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Yeomi\PostBundle\Entity\Comment", mappedBy="post", cascade={"remove"})
     */
    private $comments;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Yeomi\PostBundle\Entity\Vote", mappedBy="post", cascade={"remove"})
     */
    private $votes;
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
     * @Assert\NotBlank(message="Vous devez écrire un texte")
     * @Assert\Length(
     *      min = "10",
     *      max = "4000",
     *      minMessage = "Votre texte est trop court !",
     *      maxMessage = "Votre texte ne doit pas dépasser {{ limit }} caractères"
     * )
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
     * @var \Yeomi\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Yeomi\UserBundle\Entity\User", inversedBy="posts")
     */
    private $user;

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
     * @var \DateTime
     *
     * @ORM\Column(name="last_action", type="datetime", nullable=true)
     */
    private $lastAction;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setCreated(new \DateTime());
        $this->setPublished(true);
        $this->setLastAction(new \DateTime());

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

    /**
     * Set user
     *
     * @param \Yeomi\UserBundle\Entity\User $user
     * @return Post
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
     * @return Post
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
     * Get likes
     *
     * @return Array
     */
    public function getLikes()
    {
        $positives = 0;
        $negatives = 0;
        foreach($this->votes as $vote) {
            if ($vote->getPositive()) {
                $positives++;
            } elseif ($vote->getNegative()) {
                $negatives++;
            }
        }

        return array(
            "positives" => $positives,
            "negatives" => $negatives,
        );
    }

    /**
     * Set lastAction
     *
     * @param \DateTime $lastAction
     * @return Post
     */
    public function setLastAction($lastAction)
    {
        $this->lastAction = $lastAction;

        return $this;
    }

    /**
     * Get lastAction
     *
     * @return \DateTime 
     */
    public function getLastAction()
    {
        return $this->lastAction;
    }

    /**
     * Set video
     *
     * @param \Yeomi\PostBundle\Entity\Video $video
     * @return Post
     */
    public function setVideo(\Yeomi\PostBundle\Entity\Video $video = null)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return \Yeomi\PostBundle\Entity\Video 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        $content = $this->getContent();

        if(strpos($content, 'viagra') !== false) {
            $context->buildViolation('Merci de respecter la charte d\'utilisation')
                ->atPath('content')
                ->addViolation();
        }
        if(strpos($content, '</a>') !== false) {
            $context->buildViolation('Merci de respecter la charte d\'utilisation')
                ->atPath('content')
                ->addViolation();
        }
    }
}
