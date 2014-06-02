<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\PostTask;
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
    	
    	return $this->render('IntranetMainBundle:Task:showPosts.html.twig');
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
    		$added = PostTask::addPostByTaskId($em, $this->get('intranet.notifier'), $post);
    		
    	
    	$response = new Response(json_encode(array("result" => $added)));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
}
