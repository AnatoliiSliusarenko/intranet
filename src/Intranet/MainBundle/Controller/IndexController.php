<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Intranet\MainBundle\Services\Notifier;

class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->render('IntranetMainBundle:Index:index.html.twig');
    }
    
    public function getUserSettingsAction(){
    	$user_settings = $this->getUser()->getUserSettings();
    	$user_settings_notifications = $this->getUser()->getUserSettingsnotifications();
    	$response = new Response(json_encode(array("result" => 
    			array(
    					"user_settings" => $user_settings->getInArray(),
    					"user_settings_notifications" => $user_settings_notifications->getInArray()
    			)
    	)));
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
    
    public function showHomepageAction()
    {
    	return $this->render('IntranetMainBundle:Index:showHomepage.html.twig');
    }

}
