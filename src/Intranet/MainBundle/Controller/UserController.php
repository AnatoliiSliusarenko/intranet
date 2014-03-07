<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\User;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function addUserAction(Request $request)
    {
    	$parameters = array(
    		'name' => $request->request->get('name'),
    		'surname' => $request->request->get('surname'),
    		'email' => $request->request->get('email'),
    		'username' => $request->request->get('username'),
    		'password' => $request->request->get('password')
    	);
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	if (User::isRegisteredByEmail($em, $parameters['email']) != null)
    	{
    		$request->getSession()->set('register_error', 'User with email: "'.$parameters['email'].'" is already registered!');
    		$request->getSession()->set('register_user', $parameters);
    		return $this->redirect($this->generateUrl('intranet_security')."#register");
    	}
    	elseif (User::isRegisteredByUsername($em, $parameters['username']) != null)
    	{
    		$request->getSession()->set('register_error', 'User with username: "'.$parameters['username'].'" is already registered!');
    		$request->getSession()->set('register_user', $parameters);
    		return $this->redirect($this->generateUrl('intranet_security')."#register");
    	}
        
    	//create new user
    	$user = new User();
    	$factory = $this->get('security.encoder_factory');
    	$encoder = $factory->getEncoder($user);
    	
    	$user->setName($parameters['name']);
    	$user->setSurname($parameters['surname']);
    	$user->setEmail($parameters['email']);
    	$user->setUsername($parameters['username']);
    	$user->setPassword($encoder->encodePassword($parameters['password'], $user->getSalt()));
    	$user->setRegistered(new \DateTime());
    	$user->setLastActive(new \DateTime());
    	$user->setAvatar('eleven.png');
    	
    	$em->persist($user);
    	$em->flush();
    	
    	//set session
    	User::forceLogin($user, 'secured_area', $this->get('security.context'), $request);
    	
    	return $this->redirect($this->generateUrl('intranet_main_homepage'));
    }
}
