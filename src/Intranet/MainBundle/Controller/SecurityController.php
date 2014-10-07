<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
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
    	
    	$parameters = array('last_username' => $session->get(SecurityContext::LAST_USERNAME),
        			  'error' => $error
        );
    	
    	
        return $this->render("IntranetMainBundle:Security:login.html.twig", 
        		$parameters);
    }
    
    public function registerAction(Request $request)
    {
    	$session = $request->getSession();
    	$accessFilter = $this->get('intranet.accessFilter');
    	$clientIp = $request->getClientIp();
    	
    	//$clientIp = '58.146.96.0'; //india 
    	//$clientIp = '92.113.48.68'; //ukraine 
    	
    	$country = $accessFilter->getCountryNameByIp($clientIp);
    	$hasAccess = $accessFilter->hasAccess($clientIp);
    	
    	$register_error = $session->get('register_error');
    	$session->remove('register_error');
    	
    	$register_user = $session->get('register_user');
    	$session->remove('register_user');
    	
    	$parameters = array('register_error' => $register_error,
    				  'register_user' => $register_user,
    				  'country' => $country,
    				  'hasAccess' => $hasAccess);
    	
    	return $this->render("IntranetMainBundle:Security:register.html.twig",
    			$parameters);
    }
}
