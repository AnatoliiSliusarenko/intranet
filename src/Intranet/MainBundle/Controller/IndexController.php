<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Services\Notifier;

class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->render('IntranetMainBundle:Index:index.html.twig');
    }
    
    public function showHomepageAction()
    {
    	return $this->render('IntranetMainBundle:Index:showHomepage.html.twig');
    }

}
