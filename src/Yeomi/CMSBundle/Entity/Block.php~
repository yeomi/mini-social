<?php

namespace Yeomi\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * Block
 *
 * @ORM\Table(name="block")
 * @ORM\Entity(repositoryClass="Yeomi\CMSBundle\Repository\BlockRepository")
 */
class Block
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
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $link;

    /**
     * @var \Yeomi\PostBundle\Entity\Image
     *
     * @ORM\OneToOne(targetEntity="Yeomi\PostBundle\Entity\Image", inversedBy="block", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $image;

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
     * Set body
     *
     * @param string $body
     * @return Block
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Block
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set image
     *
     * @param \Yeomi\PostBundle\Entity\Image $image
     * @return Block
     */
    public function setImage(\Yeomi\PostBundle\Entity\Image $image = null)
    {
        $this->image = $image;
        $image->setEntityType("block");
        return $this;
    }

    /**
     * Get image
     *
     * @return \Yeomi\PostBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }
}
