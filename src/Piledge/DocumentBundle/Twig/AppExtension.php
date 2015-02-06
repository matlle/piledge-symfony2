<?php

namespace Piledge\DocumentBundle\Twig;

use Doctrine\ORM\EntityManager;

class AppExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('humanSize', array($this, 'HumanFileSize')),
            //new \Twig_SimpleFilter('nbCommentByDocument', array($this, 'countCommentByDocument')),
        );
    }


    
    public function HumanFileSize($fsize) {
    
       # size smaller then 1kb
       if ($fsize < 1024) return $fsize . ' Byte';
       # size smaller then 1mb
       if ($fsize < 1048576) return sprintf("%4.2f KB", $fsize/1024);
       # size smaller then 1gb
       if ($fsize < 1073741824) return sprintf("%4.2f MB", $fsize/1048576);
       # size smaller then 1tb
       if ($fsize < 1099511627776) return sprintf("%4.2f GB", $fsize/1073741824);
       # size larger then 1tb
      else return sprintf("%4.2f TB", $fsize/1073741824);

    }

    public function getName() {
        return 'app_extension';
    }

}
