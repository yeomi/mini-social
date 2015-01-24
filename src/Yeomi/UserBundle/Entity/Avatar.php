<?php

namespace Yeomi\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="avatar")
 * @ORM\Entity(repositoryClass="Yeomi\UserBundle\Repository\AvatarRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Avatar
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
     * @var \Yeomi\UserBundle\Entity\Profile
     *
     * @ORM\OneToOne(targetEntity="Yeomi\UserBundle\Entity\Profile", mappedBy="avatar")
     *
     */
    private $profile;

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
     * )
     */
    private $file;

    /**
     * @var string
     */
    private $tempFilename;

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
     * @return Avatar
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
     * @return Avatar
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
     * @return Avatar
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

        $this->file = null;
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

        return "uploads/img/avatar/";
    }

    public function getUploadRootDir()
    {
        return __DIR__ . "/../../../../web/" . $this->getUploadDir();
    }


    /**
     * Set profile
     *
     * @param \Yeomi\UserBundle\Entity\Profile $profile
     * @return Avatar
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
}
