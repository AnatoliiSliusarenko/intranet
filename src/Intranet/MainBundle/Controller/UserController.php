<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function addUserAction(Request $request)
    {
    	//add new user
    	//set session
    	return new Response(var_dump($request->request->all()));
    	
    	return $this->redirect($this->generateUrl('intranet_main_homepage'));
    }
}
