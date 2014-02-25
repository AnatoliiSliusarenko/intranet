<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    public function indexAction(Request $request)
    {
    	$session = $request->getSession();
    	
    	//get the login error if there is one
    	if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)){
    		$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
    	}else 
    	{
    		$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
    		$session->remove(SecurityContext::AUTHENTICATION_ERROR);
    	}
    	
        return $this->render("IntranetMainBundle:Security:index.html.twig", 
        		array('last_username' => $session->get(SecurityContext::LAST_USERNAME),
        			  'error' => $error
        ));
    }
}
