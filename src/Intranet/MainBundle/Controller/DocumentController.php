<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Intranet\MainBundle\Entity\Document;

class DocumentController extends Controller
{
    public function uploadAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	if ($request->getMethod() == 'POST') {
    		$uploadedFile = $request->files->get('userfile');
    		
    		$document = new Document($this->getUser()->getId());
    		$document->setFile($uploadedFile);
    		
    		$document->upload();
    		
    		//$uploadedFile->getClientOriginalExtension()
    		
    		
    		$em->persist($document);
    		$em->flush();
    	}
    	
    	$documents = $em->getRepository("IntranetMainBundle:Document")->findAll();
    	
        return $this->render('IntranetMainBundle:Document:upload.html.twig', array('documents' => $documents));
    }
}
