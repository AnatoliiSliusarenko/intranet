<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Intranet\MainBundle\Entity\Task;

class TaskController extends Controller
{
    public function addTaskAction(Request $request, $office_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
    	if ($office == null)
    		return new Response('Office not found!');
    	
    	if ($request->getMethod() == 'POST')
    	{
    		$name = $request->request->get('name');
    		$priority = $request->request->get('priority');
    		$status = $request->request->get('status');
    		$members = ($request->request->get('members')) ? $request->request->get('members') : array();
    		$topics = ($request->request->get('topics')) ? $request->request->get('topics') : array();
    		$backUrl = $request->request->get('backUrl');
    		
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
    	return new Response('Office not found!');
    	$topics = $office->getTopTopics($em);
    	$users = $this->getUser()->getAllUsers($em, false);
    	$backUrl = $request->query->get('backUrl');
        return $this->render('IntranetMainBundle:Task:addTask.html.twig', array("topics" => $topics, "users" => $users, "office" => $office, "backUrl" => $backUrl));
    }
    
    public function editTaskAction(Request $request, $office_id, $task_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
    	if ($office == null)
    		return new Response('Office not found!');
    	
    	$task = $em->getRepository('IntranetMainBundle:Task')->find($task_id);
    	if ($task == null)
    		return new Response('Task not found!');
    	
    	if ($request->getMethod() == 'POST')
    	{
    		$name = $request->request->get('name');
    		$priority = $request->request->get('priority');
    		$status = $request->request->get('status');
    		$members = ($request->request->get('members')) ? $request->request->get('members') : array();
    		$topics = ($request->request->get('topics')) ? $request->request->get('topics') : array();
    		$backUrl = $request->request->get('backUrl');
    		
    		$task->setName($name);
    		$task->setPriority($priority);
    		$task->setStatus($status);
    		$resetUsers = $task->resetUsers($em, $members);
    		$resetTopics = $task->resetTopics($em, $topics);
    		
    		$em->persist($task);
    		$em->flush();
    		
    		//fire notifications
    		
    		return $this->redirect($backUrl);
    	}
    	
    	$topics = $office->getTopTopics($em);
    	$users = $this->getUser()->getAllUsers($em, false);
    	$backUrl = $request->query->get('backUrl');
    	
    	return $this->render('IntranetMainBundle:Task:editTask.html.twig', array("topics" => $topics, "users" => $users, "office" => $office, "task" => $task, "backUrl" => $backUrl));
    }
}
