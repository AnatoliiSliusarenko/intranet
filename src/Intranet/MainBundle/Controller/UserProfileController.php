<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserProfileController extends Controller
{
    public function showAction()
    {
        return $this->render('IntranetMainBundle:UserProfile:showProfile.html.twig');
    }
    
    public function changeAvatarAction($document_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$document = $em->getRepository('IntranetMainBundle:Document')->find($document_id);
    	
    	$this->getUser()->setAvatar($document->getName());
    	$em->persist($this->getUser());
    	$em->flush();
    	
    	return $this->redirect($this->generateUrl('intranet_user_profile'));
    }
    
    public function changeSettingsAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$settings = $this->getUser()->getUserSettings();
    	
    	$showHiddenTopics = $request->request->get('showHiddenTopics');
    	
    	if ($showHiddenTopics == null)
    		$settings->setShowHiddenTopics(false);
    	else
    		$settings->setShowHiddenTopics(true);
    	
    	$em->persist($settings);
    	$em->flush();
    	
    	return $this->redirect($this->generateUrl('intranet_user_profile'));
    }
}
