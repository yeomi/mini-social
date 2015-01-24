<?php

namespace Yeomi\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Video
 *
 * @ORM\Table(name="video")
 * @ORM\Entity(repositoryClass="Yeomi\PostBundle\Repository\VideoRepository")
 */
class Video
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
     * @ORM\OneToOne(targetEntity="Yeomi\PostBundle\Entity\Post", mappedBy="video")
     */
    private $post;

    /**
     * @var \Yeomi\PostBundle\Entity\Comment
     * @ORM\OneToOne(targetEntity="Yeomi\PostBundle\Entity\Comment", mappedBy="video")
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", length=255)
     */
    private $video;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;


    public function __construct()
    {
        $this->setType("youtube");
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
     * Set video
     *
     * @param string $video
     * @return Video
     */
    public function setVideo($video)
    {
        $this->video = $this->getYoutubeId($video);

        return $this;
    }

    /**
     * @Assert\True(message="L'url de la vidéo youtube n'est pas valide")
     */
    public function isVideoValid()
    {
        if($this->getVideo()) {
           return true;
        } else {
            return false;
        }
    }

    /**
     * Get video
     *
     * @return string 
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Video
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set post
     *
     * @param \Yeomi\PostBundle\Entity\Post $post
     * @return Video
     */
    public function setPost(\Yeomi\PostBundle\Entity\Post $post = null)
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
     * Set comment
     *
     * @param \Yeomi\PostBundle\Entity\Comment $comment
     * @return Video
     */
    public function setComment(\Yeomi\PostBundle\Entity\Comment $comment = null)
    {
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

    /**
     * Helper function to get the youtube video's id.
     *
     * @param string $url
     *   The video URL.
     *
     * @return string|bool
     *   The video ID, or FALSE in case the ID can't be retrieved from the URL.
     */
    protected function getYoutubeId($url) {
        // Find the ID of the video they want to play from the url.
        if (stristr($url, 'http://')) {
            $url = substr($url, 7);
        }
        elseif (stristr($url, 'https://')) {
            $url = substr($url, 8);
        }

        if (stristr($url, 'playlist')) {
            // Playlists need the appended ampersand to take the options properly.
            $url = $url . '&';
            $pos = strripos($url, '?list=');
            if ($pos !== FALSE) {
                $pos2 = stripos($url, '&');
                $pos2++;
            }
            else {
                return FALSE;
            }
        }
        // Alternate playlist link.
        elseif (stristr($url, 'view_play_list')) {
            $url = $url . '&';
            // All playlist ID's are prepended with PL.
            if (!stristr($url, '?p=PL')) {
                $url = substr_replace($url, 'PL', strpos($url, '?p=') + 3, 0);
            }
            // Replace the links format with the embed format.
            $url = str_ireplace('play_list?p=', 'videoseries?list=', $url);
            $pos = strripos($url, 'videoseries?list=');
            if ($pos !== FALSE) {
                $pos2 = stripos($url, '&');
                $pos2++;
            }
            else {
                return FALSE;
            }
        }
        else {
            $pos = strripos($url, 'v=');
            if ($pos !== FALSE) {
                $pos += 2;
                $pos2 = stripos($url, '&', $pos);
                $pos_hash = stripos($url, '#', $pos);

                $pos2 = $this->getMin($pos2, $pos_hash);
            }
            else {
                $pos = strripos($url, '/');
                if ($pos !== FALSE) {
                    $pos++;
                    $pos2 = stripos($url, '?', $pos);
                    $pos_hash = stripos($url, '#', $pos);

                    $pos2 = $this->getMin($pos2, $pos_hash);
                }
            }
        }
        if ($pos === FALSE) {
            return FALSE;
        }
        else {
            if ($pos2 > 0) {
                $id = substr($url, $pos, $pos2 - $pos);
            }
            else {
                $id = substr($url, $pos);
            }
        }
        return $id;
    }
    /**
     * Calculates the min index for use in finding the id of a youtube video.
     *
     * @param string $pos1
     *   The first index.
     * @param string $pos2
     *   The second index.
     *
     * @return string
     *   The min index.
     */
    protected function getMin($pos1, $pos2) {
        if (!$pos1) {
            return $pos2;
        }
        elseif (!$pos2) {
            return $pos1;
        }
        else {
            return min($pos1, $pos2);
        }
    }

}
