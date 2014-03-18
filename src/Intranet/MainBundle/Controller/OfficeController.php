<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\Office;
use Intranet\MainBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class OfficeController extends Controller
{
	public function getOfficeMenuTreeAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		 
		$officeTree = Office::getOfficeTree($em);
	
		return $this->render("IntranetMainBundle:Office:getOfficeMenuTree.html.twig", array("officeTree" => $officeTree));
	}
	
	public function showOfficeAction(Request $request, $office_id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
		if (($office == null) || (!$office->hasUser($this->getUser())))
			return $this->redirect($this->generateUrl('intranet_main_homepage'));
		 
		$breadcrumbs = $office->getBreadcrumbs($em);
		$users = $this->getUser()->getAllUsers($em);
		
		$officeUsers = $office->getUsers();
		$childrenOfficesForUser = $office->getChildrenForUser($em, $this->getUser());
		
		$parameters = array(
				"office" => $office, 
				"breadcrumbs" => $breadcrumbs, 
				'officeUsers' => array_map(function($e){return $e->getInArray();}, $officeUsers->toArray()),
				'users' => array_map(function($e){return $e->getInArray();}, $users), 
				'offices' => $childrenOfficesForUser);
		
		
		if ($request->getSession()->has('error'))
		{
			$parameters['error'] = $request->getSession()->get('error');
			$parameters['name'] = $request->getSession()->get('name');
			$parameters['description'] = $request->getSession()->get('description');
			$request->getSession()->remove('error');
			$request->getSession()->remove('name');
			$request->getSession()->remove('description');
		}
		if ($request->getSession()->has('errorMembers'))
		{
			$parameters['errorMembers'] = $request->getSession()->get('errorMembers');
			$request->getSession()->remove('errorMembers');
		}
		
		$this->get('twig')->addGlobal('activeSection', 'office');
		return $this->render("IntranetMainBundle:Office:showOffice.html.twig", $parameters);
	}
	
	public function addOfficeAction(Request $request, $office_id)
	{
		$name = $request->request->get('name');
		$description = $request->request->get('description');
		$members = $request->request->get('members');
		
		//check for errors
		if ($name == '' || $description == '' || $members == null)
		{
			if ($members == null)
				$request->getSession()->set('error', 'In new office should be someone else besides you!');
			else
				$request->getSession()->set('error', 'Please fill out all fields!');
			
			$request->getSession()->set('name', $name);
			$request->getSession()->set('description', $description);
	
			return $this->redirect($this->generateUrl('intranet_show_office', array("office_id" => $office_id)));
		}
		 
		$em = $this->getDoctrine()->getManager();
		$members[] = $this->getUser()->getId();
		
		$office = new Office();
		$office->setOfficeid($office_id);
		$office->setName($name);
		$office->setDescription($description);
		$office->addUsers($em, $members);
		$office->setUserid($this->getUser()->getId());
		 
		
		$em->persist($office);
		$em->flush();
		 
		return $this->redirect($this->generateUrl('intranet_show_office', array('office_id' => $office->getId())));
	}
	
	public function changeOfficeMembersAction(Request $request, $office_id)
	{
		$members = $request->request->get('members');
		$em = $this->getDoctrine()->getManager();
		
		if (($members == null) || (count($members) < 2))
		{
			$request->getSession()->set('errorMembers', 'In office should be not less two members!');
			return $this->redirect($this->generateUrl('intranet_show_office', array("office_id" => $office_id)));
		}
		
		$office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
		if ($office == null)
			return $this->redirect($this->generateUrl('intranet_main_homepage'));
		
		$office->resetUsers($em, $members);
		$em->persist($office);
		$em->flush();
		
		return $this->redirect($this->generateUrl('intranet_show_office', array('office_id' => $office->getId())));
	}
}
