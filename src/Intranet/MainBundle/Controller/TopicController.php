<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\Topic;

class TopicController extends Controller
{
	public function getTopicMenuTreeAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
    	
    	$topicTree = Topic::getTopicTree($em);
		
		return $this->render("IntranetMainBundle:Topic:getTopicMenuTree.html.twig", array("topicTree" => $topicTree));
	}
	    
    public function showTopicAction($topic_id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$topic = $em->getRepository('IntranetMainBundle:Topic')->find($topic_id);
    	if ($topic == null)
    		return $this->redirect($this->generateUrl('intranet_main_homepage'));
    	
    	//$this->get('twig')->addGlobal('activeSectionId', $topic->getTopicSection()->getId());
    	
    	$breadcrumbs = $topic->getBreadcrumbs($em);
    	
    	return $this->render('IntranetMainBundle:Topic:showTopic.html.twig', array("topic" => $topic, "breadcrumbs" => $breadcrumbs));
    }
}
