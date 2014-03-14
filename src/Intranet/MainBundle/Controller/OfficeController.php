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
		if ($office == null)
			return $this->redirect($this->generateUrl('intranet_main_homepage'));
		 
		$breadcrumbs = $office->getBreadcrumbs($em);
		
		///!!!
		$users = User::getAllUsers($em);
		$offices = $this->getUser()->getOffices();
		///!!!
		
		$parameters = array("office" => $office, "breadcrumbs" => $breadcrumbs, 'users' => $users, 'offices' => $offices);
		
		
		if ($request->getSession()->has('error'))
		{
			$parameters['error'] = $request->getSession()->get('error');
			$parameters['name'] = $request->getSession()->get('name');
			$parameters['description'] = $request->getSession()->get('description');
			$request->getSession()->remove('error');
			$request->getSession()->remove('name');
			$request->getSession()->remove('description');
		}
		
		 
		return $this->render("IntranetMainBundle:Office:showOffice.html.twig", $parameters);
	}
}
