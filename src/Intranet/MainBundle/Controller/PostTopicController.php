<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\PostTopic;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PostTopicController extends Controller
{
	public function getPostsCountAction($topic_id)
	{
		$em = $this->getDoctrine()->getManager();
		
		$response = new Response(json_encode(array("result" => PostTopic::getPostsCount($em, $topic_id))));
		$response->headers->set('Content-Type', 'application/json');
		 
		return $response;
	}
	
	public function getNewPostsAction(Request $request, $topic_id)
	{
		$em = $this->getDoctrine()->getManager();
		
		$last_posted = ($request->query->get('last_posted')) ? $request->query->get('last_posted') : null ;
		
		$response = new Response(json_encode(array("result" => PostTopic::getNewPosts($em, $topic_id, $last_posted))));
		$response->headers->set('Content-Type', 'application/json');
			
		return $response;
	}
	
	public function getPostsAction(Request $request, $topic_id)
    {
    	$offset = ($request->query->get('offset')) ? $request->query->get('offset') : 0 ;
    	$limit = ($request->query->get('limit')) ? $request->query->get('limit') : 20 ;
    	
    	$repository = $this->getDoctrine()->getRepository('IntranetMainBundle:PostTopic');
    	
    	$posts = PostTopic::getPostsByTopicId($repository, $topic_id, $offset, $limit);
    	
    	$response = new Response(json_encode(array("result" => $posts)));
    	$response->headers->set('Content-Type', 'application/json');
    	
        return $response;
    }
    
    public function addPostAction($topic_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	//$data = $request->request->all();
    	$data = json_decode(file_get_contents("php://input"));
    	$post = (object) $data;
    	
    	$added = PostTopic::addPostByTopicId($em, $post);
    	
    	$response = new Response(json_encode(array("result" => $added)));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
}
