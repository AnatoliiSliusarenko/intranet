<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\Notification;
use Intranet\MainBundle\Entity\Topic;
use Intranet\MainBundle\Entity\Task;
use Intranet\MainBundle\Entity\TaskStatus;
use Intranet\MainBundle\Entity\Office;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Intranet\MainBundle\Entity\PersonalPage;
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
    	$this->get('intranet.notifier')->clearNotificationsByTopicId($topic_id);
    	$topic = $em->getRepository('IntranetMainBundle:Topic')->find($topic_id);
    	if (($topic == null) || (!$topic->getOffice()->hasUser($this->getUser())))
    		return $this->redirect($this->generateUrl('intranet_main_homepage'));
    	
    	$office = $topic->getOffice();
    	$officeTree = Office::getOfficeTree($em);
    	$breadcrumbs = $topic->getBreadcrumbs($em);
    	if ($office->getId() != $officeTree[0]->getId())
    	{
    		$officeBreadcrumbs = $office->getBreadcrumbs($em);
    		array_push($officeBreadcrumbs, $office);
    		$this->get('twig')->addGlobal('fullOfficeBreadcrumbsIds', array_map(function($e){return $e->getId();}, $officeBreadcrumbs));
    		array_shift($breadcrumbs);
    		$this->get('twig')->addGlobal('activeSection', 'office');
    	}
    	else 
    		$this->get('twig')->addGlobal('activeSection', 'topic');
    	
    	//$subtopics = $topic->getChildrenForUser($em, $this->getUser());
    	$subtopics = $topic->getChildrenForOffice($em);
    	
    	$tasks = $topic->getTasksWithChildren($em);
    	$users = $this->getUser()->getAllUsers($em, false);
    	$topicsForTasks = $topic->getAllChildrenForOffice($em);
    	array_unshift($topicsForTasks, $topic);
    	$windows = array();
    	$windows = PersonalPage::getWindowsName($em);
    	$parameters = array("users" => array_map(function($e){return $e->getInArray();}, $users), 
					    	"topic" => $topic,
					    	"em" => $em,
					    	"availableStatus" => TaskStatus::getAllStatuses($em),
					    	"breadcrumbs" => $breadcrumbs, 
					    	'subtopics' => $subtopics, 
					    	'topicsForTasks' => array_map(function($e){return $e->getInArray();}, $topicsForTasks),
					    	'office' => $office, 
					    	"tasks" => $tasks,
    						"windows" => $windows);
    	
    	if ($request->getSession()->has('errorTopic'))
    	{
    		$parameters['errorTopic'] = $request->getSession()->get('errorTopic');
    		$parameters['nameTopic'] = $request->getSession()->get('nameTopic');
    		$parameters['descriptionTopic'] = $request->getSession()->get('descriptionTopic');
    		$request->getSession()->remove('errorTopic');
    		$request->getSession()->remove('nameTopic');
    		$request->getSession()->remove('descriptionTopic');
    	}
    	
    	return $this->render('IntranetMainBundle:Topic:showTopic.html.twig', $parameters);
    }
    
    public function addTopicAction(Request $request, $topic_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$name = $request->request->get('name');
    	$description = $request->request->get('description');
    	$officeid = $request->request->get('officeid');
    	
    	//return new Response(var_dump($request->request));
    	
    	$parentTopic = $em->getRepository('IntranetMainBundle:Topic')->find($topic_id);
    	if ($parentTopic == null)
    		return $this->redirect($this->generateUrl('intranet_main_homepage'));
    	
    	if ($name == '' || $description == '')
    	{
    		$request->getSession()->set('errorTopic', 'Please fill out all fields!');
    		$request->getSession()->set('nameTopic', $name);
    		$request->getSession()->set('descriptionTopic', $description);
    		
    		return $this->redirect($request->headers->get('referer'));
    	}
    	
    	
    	$office = $em->getRepository('IntranetMainBundle:Office')->find($officeid);
    	if ($office == null)
    		return $this->redirect($this->generateUrl('intranet_main_homepage'));
    	
    	$topic = new Topic();
    	$topic->setParentid($topic_id);
    	$topic->setName($name);
    	$topic->setDescription($description);
    	$topic->setOffice($office);
    	$topic->setUserid($this->getUser()->getId());
    	$topic->setUser($this->getUser());
    	
    	$em->persist($topic);
    	$em->flush();
    	
    	$this->get('intranet.notifier')->createNotification("topic_added", $topic, $topic);
    	
    	return $this->redirect($this->generateUrl('intranet_show_topic', array('topic_id' => $topic->getId())));
    }
    
    public function deleteTopicAction($topic_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$topic = $em->getRepository('IntranetMainBundle:Topic')->find($topic_id);
    	if (($topic == null) || (($topic->getUserid() !== $this->getUser()->getId()) && (false === $this->get('security.context')->isGranted('ROLE_ADMIN'))) )
    		return $this->redirect($this->generateUrl('intranet_main_homepage'));
    
    	$this->get('intranet.notifier')->createNotification("removed_topic", $topic, $topic);
    	
    	$parent = $topic->getParentid();
    	$topic->deleteAllChildren($em);
    	$em->flush();
    
    	return $this->redirect($this->generateUrl('intranet_show_topic', array('topic_id' => $parent)));
    }
    
    public function addTopicChatToNewWindowPersonalAction($topic_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$topic = $em->getRepository('IntranetMainBundle:Topic')->find($topic_id);
    	$personal_topic = $em->getRepository('IntranetMainBundle:PersonalPage')->findByTopicid($topic_id);
    	$personal_data = $em->getRepository('IntranetMainBundle:PersonalPage')->findAll($this->getUser()->getId());
    	$count_window = count($personal_data)+1;
    	
    	if( $personal_topic != null )
    	{
    		return $this->render('IntranetMainBundle:Topic:messageTopicIsAllredyAdded.html.twig');
    	}
    	else
    	{
    		$office = $topic->getOffice();
    		$personal = new PersonalPage();
    		$personal->setOfficeid($office->getId());
    		$personal->setTopicid($topic->getId());
    		$personal->setUserid($this->getUser()->getId());
    		$personal->setNamewindow($office->getName());
    		if(!$count_window)
    			$personal->setWindowid(0);
    		else 
    			$personal->setWindowid($count_window);
    		$em = $this->getDoctrine()->getEntityManager();
    		$em->persist($personal);
    		$em->flush();
    		$parameters = array (
    				"office" => $office,
    				"topic" => $topic);
    		return $this->redirect($this->generateUrl('intranet_show_topic', array('topic_id' => $topic_id)));
    	}
    }
    
    public function addTopicToExistWindowPersonalPageAction($topic_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$topic = $em->getRepository('IntranetMainBundle:Topic')->find($topic_id);
    	
    	$personal_topic = $em->getRepository('IntranetMainBundle:PersonalPage')->findByTopicid($topic_id);
//    	$res = $personal_topic->getId();
//    	die("$res ");
    	if( $personal_topic != null )
    	{
    		return $this->render('IntranetMainBundle:Topic:messageTopicIsAllredyAdded.html.twig');
    	}
    	else
    	{
    		$office = $topic->getOffice();
    		$window = $em->getRepository('IntranetMainBundle:PersonalPage')->findOneByOfficeid($office->getId());
    		if($window == null)
    			return $this->render('IntranetMainBundle:Topic:messageTopicIsAllredyAdded.html.twig');
    		$personal = new PersonalPage();
    		$personal->setOfficeid($office->getId());
    		$personal->setTopicid($topic->getId());
    		$personal->setUserid($this->getUser()->getId());
    		$personal->setNamewindow($office->getName());
    		$personal->setWindowid($window->getWindowid());
    		$em = $this->getDoctrine()->getEntityManager();
    		$em->persist($personal);
    		$em->flush();
    		$parameters = array (
    				"office" => $office,
    				"topic" => $topic);
    		return $this->redirect($this->generateUrl('intranet_show_topic', array('topic_id' => $topic_id)));
    	}
    }
}
