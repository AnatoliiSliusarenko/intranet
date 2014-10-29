<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Intranet\MainBundle\Entity\Task;
use Intranet\MainBundle\Entity\TaskStatus;
use Intranet\MainBundle\Entity\Document;

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
				
		$data = json_decode(file_get_contents("php://input"));
		$filter = (object) $data;
		
		$response = new Response(json_encode(array("result" => $office->getTasksFilteredInArray($em, $filter))));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}
	
	public function getTasksForTopicAction(Request $request, $topic_id)
	{
		$em = $this->getDoctrine()->getManager();
		
		$topic = $em->getRepository('IntranetMainBundle:Topic')->find($topic_id);
		if ($topic == null)
		{
			$response = new Response(json_encode(array("result" => null, "message" => 'Topic not found!')));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}
		
		$data = json_decode(file_get_contents("php://input"));
		$filter = (object) $data;
		
		$response = new Response(json_encode(array("result" => $topic->getTasksFilteredInArray($em, $filter))));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}
	
    public function addTaskAction(Request $request, $office_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
    	if ($office == null)
		{
			$response = new Response(json_encode(array("result" => null, "message" => 'Office not found!')));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}
    	
    	if ($request->getMethod() == 'POST')
    	{
    		$data = json_decode(file_get_contents("php://input"));
    		$task = (object) $data;
    		
    		$name = $task->name;
    		$description = $task->description;
    		$priority = $task->priority;
    		$estimated = $task->estimated;
    		$statusid = (isset($task->statusid) && ($task->statusid != 0)) ? $task->statusid : null;
    		$userid = (isset($task->userid) && ($task->userid != 0)) ? $task->userid : null;
    		$parentid = (isset($task->parentid) && ($task->parentid != 0)) ? $task->parentid : null;
    		$topicid = (isset($task->topicid) && ($task->topicid != 0)) ? $task->topicid : null;
    		
    		$task = new Task();
    		$task->setOfficeid($office_id);
    		$task->setParentid($parentid);
    		$task->setUserid($userid);
    		$task->setTopicid($topicid);
    		$task->setStatusid($statusid);
    		$task->setEstimated($estimated);
    		
    		
    		$status = ($statusid != null) ? $em->getRepository('IntranetMainBundle:TaskStatus')->find($statusid) : null;
    		if ($status == null)
    		{
    			$response = new Response(json_encode(array("result" => null, "message" => 'Status not found!')));
    			$response->headers->set('Content-Type', 'application/json');
    			return $response;
    		}
    		$task->setStatus($status);
    		
    		$task->setOffice($office);
    		
    		$task->setName($name);
    		$task->setDescription($description);
    		$task->setPriority($priority);
    		
    		$user = ($userid != null) ? $em->getRepository('IntranetMainBundle:User')->find($userid) : null;
    		$task->setUser($user, $this->get('intranet.notifier'));
    		
    		$topic = ($topicid != null) ? $em->getRepository('IntranetMainBundle:Topic')->find($topicid): null;
    		if ($topic == null)
    		{
    			$response = new Response(json_encode(array("result" => null, "message" => 'Topic not found!')));
    			$response->headers->set('Content-Type', 'application/json');
    			return $response;
    		}
    		$task->setTopic($topic);
    		
    		$em->persist($task);
    		$em->flush();
    		
    		$this->get('intranet.taskActivityLoger')->addChangesLog($task);
    		
    		$response = new Response(json_encode(array("result" => $task->getInArray())));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
    	}
    	$parameters = array(
    		'topics' => $office->getTopics(),
    		'taskStatuses' => TaskStatus::getInitialStatuses($em)
    	);
    	$parentid = $request->query->get('parentid');
    	if ($parentid != null)
    	{
    		$parentTask = $em->getRepository('IntranetMainBundle:Task')->find($parentid);
    		if ($parentTask != null) 
    		{
    			$parameters['parentTask'] = $parentTask->getInArray();
    			//$parameters['topics'] = ($parentTask->getTopic() != null) ? array($parentTask->getTopic()) : array();
    		}
    			
    	}
    		 
        return $this->render('IntranetMainBundle:Task:addTask.html.twig', $parameters);
    }
    
    public function editTaskAction(Request $request, $task_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$task = $em->getRepository('IntranetMainBundle:Task')->find($task_id);
    	if ($task == null)
		{
			$response = new Response(json_encode(array("result" => null, "message" => 'Task not found!')));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}
		
    	if ($request->getMethod() == 'POST')
    	{
    		$this->get('intranet.taskActivityLoger')->setOldStateOfTask($task);
    		
    		$data = json_decode(file_get_contents("php://input"));
    		$taskData = (object) $data;
    		$name = $taskData->name;
    		$description = $taskData->description;
    		$priority = $taskData->priority;
    		$estimated = $taskData->estimated;
    		$statusid = (isset($taskData->statusid) && ($taskData->statusid != 0)) ? $taskData->statusid : null;
    		$userid = (isset($taskData->userid) && ($taskData->userid != 'null')) ? $taskData->userid : null;
    		$parentid = (isset($taskData->parentid)) ? $taskData->parentid : null;
    		$topicid = (isset($taskData->topicid) && ($taskData->topicid != 'null')) ? $taskData->topicid : null;
    		$status = ($statusid != null) ? $em->getRepository('IntranetMainBundle:TaskStatus')->find($statusid) : null;
    		if ($status == null)
    		{
    			$response = new Response(json_encode(array("result" => null, "message" => 'Status not found!')));
    			$response->headers->set('Content-Type', 'application/json');
    			return $response;
    		}
    		
    		$task->setUserid($userid);
    		$user = ($userid != null) ? $em->getRepository('IntranetMainBundle:User')->find($userid) : null;
    		$topic = ($topicid != null) ? $em->getRepository('IntranetMainBundle:Topic')->find($topicid) : null;
    		$task->setUser($user, $this->get('intranet.notifier'));
    		
    		
    		$task->setName($name);
    		$task->setDescription($description);
    		$task->setPriority($priority);
    		$task->setStatusid($statusid);
    		$task->setTopicid($topicid);
    		$task->setStatus($status);
    		$task->setParentid($parentid);
    		$task->setEstimated($estimated);
    		$task->setTopic($topic);
    		$em->persist($task);
    		$em->flush();
    		
    		$this->get('intranet.taskActivityLoger')->addChangesLog($task);
    		$response = new Response(json_encode(array("result" => $task->getInArray())));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
    	}
    	$timestamp = time();
    	$token = Document::getToken($timestamp);
    	$userid = $task->getUserid($task->getUserid());
    	if ($userid != null)
    	{
    		$user = $em->getRepository('IntranetMainBundle:User')->find($userid);
    		$avatar = $user->getAvatar();
    	}
    	else 
    		$avatar = null;
    	$availableStatus = $task->getAvailableStatus($this->getUser());
    	$parameters = array(
    			'userAvatar'=> $avatar,
    			'availableStatus' => $availableStatus,
    			'topics' => $task->getOffice()->getTopics(),
    			'timestamp' => $timestamp,
    			'token' => $token,
    			'taskName' => $task->getName(),
    			'taskDescription' => $task->getDescription(),
    			'availableTypes'=> Document::getAvailableTypesInString(),
    			'task' => $task
    	);
    	
    	if ($task->getParentid() != null)
    	{
    		$parentTask = $em->getRepository('IntranetMainBundle:Task')->find($task->getParentid());
    		if ($parentTask!=null)
    		{
    			//$parameters['topics'] = ($parentTask->getTopic() != null) ? array($parentTask->getTopic()) : array();
    		}
    	}
    		
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
    
    public function resetNewCommentsAction($task_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$task = $em->getRepository('IntranetMainBundle:Task')->find($task_id);
    	if ($task == null)
    	{
    		$response = new Response(json_encode(array("result" => null, "message" => 'Task not found!')));
    		$response->headers->set('Content-Type', 'application/json');
    		return $response;
    	}
    	$this->get('intranet.notifier')->clearNotificationsByTaskId($task_id);
    	
    	$response = new Response(json_encode(array("result" => $task_id)));
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
}
