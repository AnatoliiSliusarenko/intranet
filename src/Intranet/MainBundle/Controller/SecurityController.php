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
    		
    		$register_error = $session->get('register_error');
    		$session->remove('register_error');
    		
    		$register_user = $session->get('register_user');
    		$session->remove('register_user');
    	}
    	
    	$parameters = array('last_username' => $session->get(SecurityContext::LAST_USERNAME),
        			  'error' => $error,
        			  'register_error' => $register_error,
    				  'register_user' => $register_user
        );
    	
    	
        return $this->render("IntranetMainBundle:Security:index.html.twig", 
        		$parameters);
    }
}
