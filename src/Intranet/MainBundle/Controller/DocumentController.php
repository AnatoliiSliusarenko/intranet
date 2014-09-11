<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Intranet\MainBundle\Entity\Document;

class DocumentController extends Controller
{
    public function indexAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$timestamp = time();
    	$token = Document::getToken($timestamp);
    	
        return $this->render('IntranetMainBundle:Document:upload.html.twig', 
        		array('timestamp' => $timestamp,
        			  'token' => $token,
        			  'availableTypes'=> Document::getAvailableTypesInString()
        ));
    }
    
    public function uploadAction(Request $request)
    {
    	if ($request->getMethod() == 'POST') {
    		
    		$uploadedFile = $request->files->get('Filedata');
    		
    		$document = new Document($this->getUser());
    		
    		$timestamp = $request->request->get('timestamp');
    		$token = $request->request->get('token');
    		
    		$document->setFile($uploadedFile, $timestamp, $token);
    		
    		if ($document->upload())
    		{
    			$em = $this->getDoctrine()->getManager();
    			$em->persist($document);
    			$em->flush();
    			return new Response('Document uploaded...');
    		}
    		return new Response('Document not uploaded...');
    	}else
    		return new Response('Works only throw POST...');
    }
    
    public function getDocumentsAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$userid = $request->query->get('userid');
    	
    	$documents = Document::getAllDocuments($em, $userid);
    	
    	$response = new Response(json_encode(array("result" => $documents)));
    	$response->headers->set('Content-Type', 'application/json');
    		
    	return $response;
    }
    
    public function addDialogAction()
    {
    	$timestamp = time();
    	$token = Document::getToken($timestamp);
    	
    	return $this->render('IntranetMainBundle:Document:addDialog.html.twig', array(
    			'timestamp' => $timestamp,
        		'token' => $token,
        		'availableTypes'=> Document::getAvailableTypesInString()
        ));
    }
}
