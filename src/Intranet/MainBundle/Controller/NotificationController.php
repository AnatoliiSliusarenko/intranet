<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class NotificationController extends Controller
{
    public function showNotificationsAction()
    {
        return $this->render('IntranetMainBundle:Notification:showNotifications.html.twig');
    }
    
    public function getNotificationsAction(Request $request)
    {
    	if (!$request->isXmlHttpRequest())
    	{
    		return $this->redirect($this->generateUrl('intranet_main_homepage'));
    	}
    	
    	$response = new Response(json_encode(array("result" => array_map(function($e){return $e->getInArray();}, $this->getUser()->getNotifications()->toArray()))));
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
    
    public function clearNotificationsAction()
    {
    	$this->getUser()->clearNotifications($this->getDoctrine()->getManager());
    	return $this->redirect($this->generateUrl('intranet_notifications_show'));
    }
}
