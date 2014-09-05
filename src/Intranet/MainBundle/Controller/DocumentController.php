<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DocumentController extends Controller
{
    public function uploadAction(Request $request)
    {
    	
    	$image = null;
    	
    	if ($request->getMethod() == 'POST') {
    		$uploadedFile = $request->files->get('userfile');
    		
    		$uploadedFile->move('documents', $uploadedFile->getClientOriginalName());
    	
    		//$uploadedFile->getClientOriginalExtension()
    		
    		$image = $uploadedFile->getClientOriginalName();
    		
    	}
    	
        return $this->render('IntranetMainBundle:Document:upload.html.twig', array('image'=> $image));
    }
}
