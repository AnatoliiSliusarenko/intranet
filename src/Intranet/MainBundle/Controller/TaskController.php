<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Intranet\MainBundle\Entity\Task;

class TaskController extends Controller
{
	public function getTasksForOfficeAction(Request $request, $office_id)
	{
		$em = $this->getDoctrine()->getManager();
		
		$office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
		if ($office == null)
		{
			$response = new Response(json_encode(array("result" => null, "message" => 'Office not found!')));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}
				
		$response = new Response(json_encode(array("result" => $office->getTasksInArray())));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
		
		
	}
	
	public function getTasksForTopicAction(Request $request, $topic_id)
	{
	
	}
	
    public function addTaskAction(Request $request, $office_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$backUrl = $request->query->get('backUrl');
    	if ($backUrl == null)
    		$backUrl = $this->generateUrl('intranet_main_homepage');
    	
    	$office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
    	if ($office == null)
    		return $this->redirect($backUrl);
    	
    	$topicid = $request->query->get('topicid');
    	$topic = ($topicid == null) ? null: $em->getRepository('IntranetMainBundle:Topic')->find($topicid);
    	
    	if ($request->getMethod() == 'POST')
    	{
    		$name = $request->request->get('name');
    		$priority = $request->request->get('priority');
    		$status = $request->request->get('status');
    		$members = ($request->request->get('members')) ? $request->request->get('members') : array();
    		$topics = ($request->request->get('topics')) ? $request->request->get('topics') : array();
    		$backUrl = $request->request->get('backUrl');
    		$topicid = $request->request->get('topicid');
    		if ($topicid != null)
    			$topics = array($topicid);
    		
    		$task = new Task();
    		$task->setOfficeid($office_id);
    		$task->setOffice($office);
    		$task->setName($name);
    		$task->setPriority($priority);
    		$task->setStatus($status);
    		$usersAdded = $task->addUsers($em, $members);
    		$topicsAdded = $task->addTopics($em, $topics);
    		
    		$em->persist($task);
    		$em->flush();
    		
    		//fire notifications
    		
    		return $this->redirect($backUrl);
    	}
    	
    	$topics = $office->getTopics();
    	$users = $this->getUser()->getAllUsers($em, false);
    	
    	$parameters = array("topics" => $topics, "users" => $users, "office" => $office, "backUrl" => $backUrl);
    	
    	if ($topic != null)
    		$parameters['topic'] = $topic;
    	
        return $this->render('IntranetMainBundle:Task:addTask.html.twig', $parameters);
    }
    
    public function editTaskAction(Request $request, $office_id, $task_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$backUrl = $request->query->get('backUrl');
    	if ($backUrl == null)
    		$backUrl = $this->generateUrl('intranet_main_homepage');
    	
    	$office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
    	if ($office == null)
    		return $this->redirect($backUrl);
    	
    	$task = $em->getRepository('IntranetMainBundle:Task')->find($task_id);
    	if ($task == null)
    		return $this->redirect($backUrl);
    	
    	$topicid = $request->query->get('topicid');
    	$topic = ($topicid == null) ? null: $em->getRepository('IntranetMainBundle:Topic')->find($topicid);
    	
    	if ($request->getMethod() == 'POST')
    	{
    		$name = $request->request->get('name');
    		$priority = $request->request->get('priority');
    		$status = $request->request->get('status');
    		$members = ($request->request->get('members')) ? $request->request->get('members') : array();
    		$topics = ($request->request->get('topics')) ? $request->request->get('topics') : array();
    		$backUrl = $request->request->get('backUrl');
    		$topicid = $request->request->get('topicid');
    		if ($topicid == null)
    			$resetTopics = $task->resetTopics($em, $topics);
    		
    		$task->setName($name);
    		$task->setPriority($priority);
    		$task->setStatus($status);
    		$resetUsers = $task->resetUsers($em, $members);
    		
    		$em->persist($task);
    		$em->flush();
    		
    		//fire notifications
    		
    		return $this->redirect($backUrl);
    	}
    	
    	$topics = $office->getTopics();
    	$users = $this->getUser()->getAllUsers($em, false);
    	
    	
    	$parameters = array("topics" => $topics, "users" => $users, "office" => $office, "task" => $task, "backUrl" => $backUrl);
    	
    	if ($topic != null)
    		$parameters['topic'] = $topic;
    	
    	return $this->render('IntranetMainBundle:Task:editTask.html.twig', $parameters);
    }
    
    public function removeTaskAction(Request $request, $task_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$task = $em->getRepository('IntranetMainBundle:Task')->find($task_id);
    	if ($task == null)
    	{
    		$response = new Response(json_encode(array("result" => null, "message" => 'Task not found!')));
    		$response->headers->set('Content-Type', 'application/json');
    		return $response;
    	}
    	$em->remove($task);
    	$em->flush();
    	
    	$response = new Response(json_encode(array("result" => $task_id)));
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
}
