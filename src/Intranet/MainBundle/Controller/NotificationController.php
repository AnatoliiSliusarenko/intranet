<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends Controller
{
    public function showNotificationsAction()
    {
        return $this->render('IntranetMainBundle:Notification:showNotifications.html.twig');
    }
    
    public function getNotificationsAction()
    {
    	$response = new Response(json_encode(array("result" => array_map(function($e){return $e->getInArray();}, $this->getUser()->getNotifications()->toArray()))));
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
}
