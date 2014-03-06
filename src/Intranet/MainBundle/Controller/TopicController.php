<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\Topic;
use Symfony\Component\HttpFoundation\Request;

class TopicController extends Controller
{
	public function getTopicMenuTreeAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
    	
    	$topicTree = Topic::getTopicTree($em);
		
		return $this->render("IntranetMainBundle:Topic:getTopicMenuTree.html.twig", array("topicTree" => $topicTree));
	}
	    
    public function showTopicAction(Request $request, $topic_id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$topic = $em->getRepository('IntranetMainBundle:Topic')->find($topic_id);
    	if ($topic == null)
    		return $this->redirect($this->generateUrl('intranet_main_homepage'));
    	
    	$breadcrumbs = $topic->getBreadcrumbs($em);
    	
    	$parameters = array("topic" => $topic, "breadcrumbs" => $breadcrumbs);
    	
    	if ($request->getSession()->has('error'))
    	{
    		$parameters['error'] = $request->getSession()->get('error');
    		$parameters['name'] = $request->getSession()->get('name');
    		$parameters['description'] = $request->getSession()->get('description');
    		$request->getSession()->remove('error');
    		$request->getSession()->remove('name');
    		$request->getSession()->remove('description');
    	}
    	
    	return $this->render('IntranetMainBundle:Topic:showTopic.html.twig', $parameters);
    }
    
    public function addTopicAction(Request $request, $topic_id)
    {
    	$name = $request->request->get('name');
    	$description = $request->request->get('description');
    	
    	if ($name == '' || $description == '')
    	{
    		$request->getSession()->set('error', 'Please fill out all fields!');
    		$request->getSession()->set('name', $name);
    		$request->getSession()->set('description', $description);
    		
    		return $this->redirect($this->generateUrl('intranet_show_topic', array("topic_id" => $topic_id)));
    	}
    	
    	$topic = new Topic();
    	$topic->setParentid($topic_id);
    	$topic->setName($name);
    	$topic->setDescription($description);
    	
    	$em = $this->getDoctrine()->getManager();
    	$em->persist($topic);
    	$em->flush();
    	
    	return $this->redirect($this->generateUrl('intranet_show_topic', array('topic_id' => $topic->getId())));
    }
}
