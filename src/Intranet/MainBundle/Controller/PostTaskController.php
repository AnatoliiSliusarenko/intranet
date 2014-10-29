<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\PostTask;
use Intranet\MainBundle\Entity\Document;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PostTaskController extends Controller
{
	public function showPostsAction(Request $request, $task_id)
    {
    	$task = $this->getDoctrine()->getRepository('IntranetMainBundle:Task')->find($task_id);
		if ($task == null)
		{
			$response = new Response(json_encode(array("result" => null, "message" => 'Task not found!')));
			$response->headers->set('Content-Type', 'application/json');
			return $response;
		}
    	
		$timestamp = time();
		$token = Document::getToken($timestamp);
		
    	return $this->render('IntranetMainBundle:Task:showPosts.html.twig', 
    			array('timestamp' => $timestamp,
        			  'token' => $token,
        			  'availableTypes'=> Document::getAvailableTypesInString()
        ));
    }
    
    public function getPostsAction(Request $request, $task_id)
    {
    	$task = $this->getDoctrine()->getRepository('IntranetMainBundle:Task')->find($task_id);
    	if ($task == null)
    	{
    		$response = new Response(json_encode(array("result" => null, "message" => 'Task not found!')));
    		$response->headers->set('Content-Type', 'application/json');
    		return $response;
    	}
    	 
    	$response = new Response(json_encode(array("result" => array_map(function($e){return $e->getInArray();}, $task->getPosts()->toArray()))));
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
    
    public function addPostAction(Request $request, $task_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$task = $this->getDoctrine()->getRepository('IntranetMainBundle:Task')->find($task_id);
    	if ($task == null)
    	{
    		$response = new Response(json_encode(array("result" => null, "message" => 'Task not found!')));
    		$response->headers->set('Content-Type', 'application/json');
    		return $response;
    	}
    	//$data = $request->request->all();
    	$data = json_decode(file_get_contents("php://input"));
    	$post = (object) $data;
    	
    	if (isset($post->postid))
    		$added = PostTask::editPostByTaskId($em, $post);
    	else
    	{
    		$added = PostTask::addPostByTaskId($em, $this->get('intranet.notifier'), $post);
    		$this->get('intranet.taskActivityLoger')->addCommentLog($task, $added);
    	}	
    	
    	$response = new Response(json_encode(array("result" => $added)));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    public function sendPrivateMsgAction($task_id){
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$post = (object) $data;
    	$user_to_send_name = $post->usertosendname;
    	$task = $em->getRepository("IntranetMainBundle:Task")->find($task_id);
    	$notifier = $this->get('intranet.notifier');
    	$notifier->createNotification("private_message_task",$this->getUser(),$task,$user_to_send_name);
    	$response = new Response(json_encode(array("result" => 'res')));
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
    
}
