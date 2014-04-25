<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\PostOffice;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PostOfficeController extends Controller
{
	public function getPostsCountAction($office_id)
	{
		$em = $this->getDoctrine()->getManager();
		
		$response = new Response(json_encode(array("result" => PostOffice::getPostsCount($em, $office_id))));
		$response->headers->set('Content-Type', 'application/json');
		 
		return $response;
	}
	
	public function getNewPostsAction(Request $request, $office_id)
	{
		$em = $this->getDoctrine()->getManager();
		
		$last_posted = ($request->query->get('last_posted')) ? $request->query->get('last_posted') : null ;
		
		$response = new Response(json_encode(array("result" => PostOffice::getNewPosts($em, $office_id, $last_posted))));
		$response->headers->set('Content-Type', 'application/json');
			
		return $response;
	}
	
	public function getPostsAction(Request $request, $office_id)
    {
    	$offset = ($request->query->get('offset')) ? $request->query->get('offset') : 0 ;
    	$limit = ($request->query->get('limit')) ? $request->query->get('limit') : 20 ;
    	
    	$repository = $this->getDoctrine()->getRepository('IntranetMainBundle:PostOffice');
    	
    	$posts = PostOffice::getPostsByOfficeId($repository, $office_id, $offset, $limit);
    	
    	$response = new Response(json_encode(array("result" => $posts)));
    	$response->headers->set('Content-Type', 'application/json');
    	
        return $response;
    }
    
    public function addPostAction($office_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	//$data = $request->request->all();
    	$data = json_decode(file_get_contents("php://input"));
    	$post = (object) $data;
    	
    	if (isset($post->postid))
    		$added = PostOffice::editPostByOfficeId($em, $post);
    	else
    		$added = PostOffice::addPostByOfficeId($em, $this->get('mailer'), $post);
    	
    	$response = new Response(json_encode(array("result" => $added)));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
}
