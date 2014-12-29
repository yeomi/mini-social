<?php
/**
 * Created by PhpStorm.
 * User: bindou
 * Date: 29/12/2014
 * Time: 13:13
 */
namespace Yeomi\PostBundle\Tools;

class YeomiTruncate extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
          "truncatePost" => new \Twig_SimpleFunction("truncatePost", array($this, "truncate"))
        );
    }
    public function getName()
    {
        return "YeomiTruncate";
    }

    public function truncate($var, $size)
    {
        if(strlen($var) > $size) {
            return substr($var, 0, $size) . " ...";
        }
        return $var;
    }

} 