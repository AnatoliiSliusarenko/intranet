<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
	public function getPostsAction(Request $request, $topic_id)
    {
    	if (!$request->isXmlHttpRequest())
    		return new Response('It is used only with AJAX!'); 
    		
    	$offset = ($request->query->get('offset')) ? $request->query->get('offset') : 0 ;
    	$limit = ($request->query->get('limit')) ? $request->query->get('limit') : 20 ;
    	
    	$repository = $this->getDoctrine()->getRepository('IntranetMainBundle:Post');
    	
    	$posts = Post::getPostsByTopicId($repository, $topic_id, $offset, $limit);
    	
    	$response = new Response(json_encode($posts));
    	$response->headers->set('Content-Type', 'application/json');
    	
        return $response;
    }
    
    public function addPostAction(Request $request, $topic_id)
    {
    	//if (!$request->isXmlHttpRequest())
    		//return new Response('It is used only with AJAX!');
    		
    	$post = $request->request->all();
    	Post::addPostByTopicId($repository, $topic_id, $post);
    	
    	$response = new Response(json_encode($post));
    	
    	return $response;
    }
}
