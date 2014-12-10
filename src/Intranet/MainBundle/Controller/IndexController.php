<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Services\Notifier;
use Intranet\MainBundle\Controller\OfficeController;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    /**
     *
     */
    public function indexAction()
    {
        $office_id = $this->getUser()->getLastOfficeId();
        if($office_id == null)
            return $this->render('IntranetMainBundle:Index:index.html.twig');
        else
        {
            $em = $this->getDoctrine()->getManager();
            $office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
            $client = new OfficeController();
            $client->setContainer($this->container);
            $request = new Request();
            $client->showOfficeAction($request, $office_id);
            return $this->render('IntranetMainBundle:Index:index.html.twig');
        }
    }
    
    public function showHomepageAction()
    {
    	return $this->render('IntranetMainBundle:Index:showHomepage.html.twig');
    }

}
