<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\Topic;
use Intranet\MainBundle\Entity\Office;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TopicController extends Controller
{
	public function getTopicMenuTreeAction()
	{
		$em = $this->getDoctrine()->getManager();
    	
    	$topicTree = Topic::getTopicTree($em);
		
		return $this->render("IntranetMainBundle:Topic:getTopicMenuTree.html.twig", array("topicTree" => $topicTree));
	}
	    
    public function showTopicAction(Request $request, $topic_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$topic = $em->getRepository('IntranetMainBundle:Topic')->find($topic_id);
    	if (($topic == null) || (($topic->getOfficeid() != 0) && (!$topic->getOffice()->hasUser($this->getUser()))))
    		return $this->redirect($this->generateUrl('intranet_main_homepage'));
    	
    	$breadcrumbs = $topic->getBreadcrumbs($em);
    	$subtopics = $topic->getChildrenForUser($em, $this->getUser());
    	
    	if ($topic->getOfficeid() == 0)
    	{
    		$offices = $this->getUser()->getOffices()->toArray();
    		$tree = Office::getOfficeTree($em);
    		$publicOffice = $tree[0];
    		$key = array_search($publicOffice, $offices, true);
    		if ($key !== false)
    			unset($offices[$key]);
    		array_unshift($offices, $publicOffice->getInArray());
    	}else 
    	{
    		$offices = array($topic->getOffice());
    	}
    	
    	
    	$parameters = array("topic" => $topic, "breadcrumbs" => $breadcrumbs, 'offices' => $offices, 'subtopics' => $subtopics);
    	
    	if ($request->getSession()->has('error'))
    	{
    		$parameters['error'] = $request->getSession()->get('error');
    		$parameters['name'] = $request->getSession()->get('name');
    		$parameters['description'] = $request->getSession()->get('description');
    		$request->getSession()->remove('error');
    		$request->getSession()->remove('name');
    		$request->getSession()->remove('description');
    	}
    	
    	$this->get('twig')->addGlobal('activeSection', 'topic');
    	return $this->render('IntranetMainBundle:Topic:showTopic.html.twig', $parameters);
    }
    
    public function addTopicAction(Request $request, $topic_id)
    {
    	$name = $request->request->get('name');
    	$description = $request->request->get('description');
    	$officeid = $request->request->get('officeid');
    	
    	if ($name == '' || $description == '')
    	{
    		$request->getSession()->set('error', 'Please fill out all fields!');
    		$request->getSession()->set('name', $name);
    		$request->getSession()->set('description', $description);
    		
    		return $this->redirect($this->generateUrl('intranet_show_topic', array("topic_id" => $topic_id)));
    	}
    	
    	$em = $this->getDoctrine()->getManager();
    	$office = $em->getRepository('IntranetMainBundle:Office')->find($officeid);
    	if ($office == null)
    		return $this->redirect($this->generateUrl('intranet_main_homepage'));
    	
    	$topic = new Topic();
    	$topic->setParentid($topic_id);
    	$topic->setName($name);
    	$topic->setDescription($description);
    	$topic->setOffice($office);
    	
    	$em->persist($topic);
    	$em->flush();
    	
    	return $this->redirect($this->generateUrl('intranet_show_topic', array('topic_id' => $topic->getId())));
    }
}
