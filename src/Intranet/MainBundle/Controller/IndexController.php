<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->render('IntranetMainBundle:Index:index.html.twig');
    }
    
    public function getUserSettingsAction(){
    	$user_settings = $this->getUser()->getUserSettings();
    	$response = new Response(json_encode(array("result" => $user_settings->getInArray())));
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
}
