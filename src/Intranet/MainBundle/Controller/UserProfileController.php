<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
