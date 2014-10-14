<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\User;
use Intranet\MainBundle\Entity\UserSettings;
use Intranet\MainBundle\Entity\Document;
use Intranet\MainBundle\Entity\Office;
use Intranet\MainBundle\Entity\Role;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function addUserAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	
    	$parameters = array(
    		'name' => $data->name,
    		'surname' => $data->surname,
    		'email' => $data->email,
    		'username' => $data->username,
    		'password' => $data->password,
    		'role' => $data->role,
    		'country' => $data->country
    	);
    	
    	$em = $this->getDoctrine()->getManager();
    	//create new user
    	$user = User::addUser($em, $this->get('security.encoder_factory'), $parameters);
    	//set session
    	User::forceLogin($user, 'secured_area', $this->get('security.context'), $request);
    	
    	$userOffices = $user->getOffices();
    	$office = $userOffices[0];
    	$response = new Response(json_encode(array("result" => true, 
    			'redirect' => $this->generateUrl('intranet_show_office', array('office_id' => $office->getId())))));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    public function getTopicMembersAction($topic_id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => User::getTopicMembers($em, $topic_id, true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function getOfficeMembersAction($office_id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$response = new Response(json_encode(array("result" => User::getOfficeMembers($em, $office_id, true))));
    	$response->headers->set('Content-Type', 'application/json');
    
    	return $response;
    }
    
    public function checkUsernameAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$username = $data->username;
    	
    	$result = (User::isRegisteredByUsername($em, $username) != null) ? false : true; 
    	
    	$response = new Response(json_encode(array("result" => $result)));
    	$response->headers->set('Content-Type', 'application/json');
    	
    	return $response;
    }
    
    public function checkEmailAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$data = json_decode(file_get_contents("php://input"));
    	$email = $data->email;
    	 
    	$result = (User::isRegisteredByEmail($em, $email) != null) ? false : true;
    	 
    	$response = new Response(json_encode(array("result" => $result)));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
}
