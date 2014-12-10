<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Services\Notifier;
use Intranet\MainBundle\Entity\Office;

class IndexController extends Controller
{
    public function indexAction()
    {
        $id = $this->getUser()->getLastOfficeId();
        if($id == null)
            return $this->render('IntranetMainBundle:Index:index.html.twig');
        else
        {
            $em = $this->getDoctrine()->getManager();
            $office = $em->getRepository('IntranetMainBundle:Office')->find($id);
            $parameters = Office::getParameters($office, $em, $this->getUser(), false);

            return $this->render("IntranetMainBundle:Office:showOffice.html.twig",$parameters);
        }
    }
    
    public function showHomepageAction()
    {
    	return $this->render('IntranetMainBundle:Index:showHomepage.html.twig');
    }

}
