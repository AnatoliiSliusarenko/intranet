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
    		$users = (isset($task->users)) ? $task->users : array();
    		$topics = (isset($task->topics)) ? $task->topics : array();
    	
    		$task = new Task();
    		$task->setOfficeid($office_id);
    		$task->setOffice($office);
    		$task->setName($name);
    		$task->setPriority($priority);
    		$task->setStatus($status);
    		$usersAdded = $task->addUsers($em, $users);
    		$topicsAdded = $task->addTopics($em, $topics);
    		
    		$em->persist($task);
    		$em->flush();
    		
    		//fire notifications
    		
    		$response = new Response(json_encode(array("result" => $task->getInArray())));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
    	}
    	
        return $this->render('IntranetMainBundle:Task:addTask.html.twig');
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
    		$users = (isset($taskData->users)) ? $taskData->users : array();
    		$topics = (isset($taskData->topics)) ? $taskData->topics : array();
    		
    		$task->setName($name);
    		$task->setPriority($priority);
    		$task->setStatus($status);
    		$resetUsers = $task->resetUsers($em, $users);
    		$resetTopics = $task->resetTopics($em, $topics);
    		
    		$em->persist($task);
    		$em->flush();
    		
    		//fire notifications
    		
    		$response = new Response(json_encode(array("result" => $task->getInArray())));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
    	}
    	
    	return $this->render('IntranetMainBundle:Task:editTask.html.twig');
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
