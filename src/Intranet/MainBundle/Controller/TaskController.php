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
    		$priority = $task->priority;
    		$status = $task->status;
    		$userid = (isset($task->userid)) ? $task->userid : null;
    		$parentid = (isset($task->parentid)) ? $task->parentid : null;
    		$topics = (isset($task->topics)) ? $task->topics : array();
    		
    		$task = new Task();
    		$task->setOfficeid($office_id);
    		$task->setParentid($parentid);
    		$task->setUserid($userid);
    		
    		$user = ($userid != null) ? $em->getRepository('IntranetMainBundle:User')->find($userid) : null;
    		$task->setUser($user);
    		
    		$task->setOffice($office);
    		$task->setName($name);
    		$task->setPriority($priority);
    		$task->setStatus($status);
    		$topicsAdded = $task->addTopics($em, $topics);
    		
    		$em->persist($task);
    		$em->flush();
    		
    		//fire notifications
    		
    		$response = new Response(json_encode(array("result" => $task->getInArray())));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
    	}
    	
    	$parameters = array();
		$parameters['topics'] = $office->getTopics();
    	
    	$parentid = $request->query->get('parentid');
    	if ($parentid != null)
    	{
    		$parentTask = $em->getRepository('IntranetMainBundle:Task')->find($parentid);
    		if ($parentTask != null) 
    		{
    			$parameters['parentTask'] = $parentTask->getInArray();
    			$parameters['topics'] = $parentTask->getTopics();
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
    		$data = json_decode(file_get_contents("php://input"));
    		$taskData = (object) $data;
    		
    		$name = $taskData->name;
    		$priority = $taskData->priority;
    		$status = $taskData->status;
    		$userid = (isset($taskData->userid)) ? $taskData->userid : null;
    		$parentid = (isset($taskData->parentid)) ? $taskData->parentid : null;
    		$topics = (isset($taskData->topicsIds)) ? $taskData->topicsIds : array();
    		
    		$task->setUserid($userid);
    		$user = ($userid != null) ? $em->getRepository('IntranetMainBundle:User')->find($userid) : null;
    		$task->setUser($user);
    		
    		
    		$task->setName($name);
    		$task->setPriority($priority);
    		$task->setStatus($status);
    		$task->setParentid($parentid);
    		$resetTopics = $task->resetTopics($em, $topics);
    		
    		$em->persist($task);
    		$em->flush();
    		
    		//fire notifications
    		
    		$response = new Response(json_encode(array("result" => $task->getInArray())));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
    	}
    	
    	$availableStatus = $task->getVisibleAvailableStatus();
    	$parameters = array(
    			'availableStatus' => $availableStatus,
    			'topics' => $task->getOffice()->getTopics()
    	);
    	if ($task->getParentid() != null)
    	{
    		$parentTask = $em->getRepository('IntranetMainBundle:Task')->find($task->getParentid());
    		if ($parentTask!=null)
    		{
    			$parameters['topics'] = $parentTask->getTopics();
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
}
