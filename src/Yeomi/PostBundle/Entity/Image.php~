<?php

namespace Yeomi\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="Yeomi\PostBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
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
     * @var \Yeomi\PostBundle\Entity\Post
     *
     * @ORM\ManyToOne(targetEntity="Yeomi\PostBundle\Entity\Post", inversedBy="images")
     *
     */
    private $post;

    /**
     * @var \Yeomi\PostBundle\Entity\Comment
     *
     * @ORM\ManyToOne(targetEntity="Yeomi\PostBundle\Entity\Comment", inversedBy="images")
     *
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @var UploadedFile
     * @Assert\Image(
     *     maxWidth = 1200,
     *     maxHeight = 1200,
     *
     * )
     */
    private $file;

    /**
     * @var string
     */
    private $tempFilename;

    /**
     * @var string
     */
    private $entityType;

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
     * Set value
     *
     * @param string $value
     * @return Image
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Image
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
     * Set alt
     *
     * @param string $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set post
     *
     * @param \Yeomi\PostBundle\Entity\Post $post
     * @return Image
     */
    public function setPost(\Yeomi\PostBundle\Entity\Post $post)
    {
        $this->entityType = "post";
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
     * Get file
     *
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get file
     *
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function setFile(UploadedFile $file = null)
    {

        $this->file = $file;

        if ($this->value != null) {
            $this->tempFilename = $this->value;

            $this->value = null;
            $this->alt = null;
            $this->title = null;
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {

        if (null == $this->file) {
            return;
        }

        $this->value = $this->file->guessExtension();
        $this->alt = $this->file->getClientOriginalName();
        $this->title = $this->file->getClientOriginalName();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if(null == $this->file) {
            return;
        }

        if ($this->tempFilename != null) {
            $oldFile = $this->getUploadRootDir() . "/" . $this->id . "." . $this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $this->file->move(
            $this->getUploadRootDir(),
            $this->id . "." . $this->value
        );
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->tempFilename = $this->getUploadRootDir() . "/" . $this->id . "." . $this->value;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if(file_exists($this->tempFilename)) {
            unlink($this->tempFilename);
        }
    }

    public function getUploadDir()
    {

        return "uploads/img/" . $this->entityType;
    }

    public function getUploadRootDir()
    {
        return __DIR__ . "/../../../../web/" . $this->getUploadDir();
    }


    /**
     * Set comment
     *
     * @param \Yeomi\PostBundle\Entity\Comment $comment
     * @return Image
     */
    public function setComment(\Yeomi\PostBundle\Entity\Comment $comment = null)
    {
        $this->entityType = "comment";
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \Yeomi\PostBundle\Entity\Comment 
     */
    public function getComment()
    {
        return $this->comment;
    }
}
