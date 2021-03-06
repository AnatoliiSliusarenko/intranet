<?php
namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\TaskStatus;
use Intranet\MainBundle\Entity\Topic;
use Intranet\MainBundle\Entity\PersonalPage;
class PersonalPageController extends Controller
{	
	public  function showPersonalPageAction()
	{
		
		$em = $this->getDoctrine()->getManager();
		$users = $this->getUser()->getAllUsers($em, false);
		$parameters = PersonalPage::getDataForUser($em, $this->getUser()->getId());
		if($parameters == null)
			return $this->redirect($this->generateUrl('intranet_main_homepage'));
		return $this->render('IntranetMainBundle:PersonalPage:showPersonalPage.html.twig', $parameters);
	}
	
	public function showChatPersonalPageAction($office_id , $topics, $window, $curent)
	{
		$em = $this->getDoctrine()->getManager();
		$office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
		if (($office == null) || (!$office->hasUser($this->getUser())))
			return $this->redirect($this->generateUrl('intranet_main_homepage'));
			
		$breadcrumbs = $office->getBreadcrumbs($em);
		$users = $this->getUser()->getAllUsers($em, false);
		
		$officeUsers = $office->getUsers();
		$childrenOfficesForUser = $office->getChildrenForUser($em, $this->getUser());
		
		$topicTree = Topic::getTopicTree($em);
		$parentTopic = $topicTree[0];
		$data = PersonalPage::getTopicsForWindow($window->getWindowid(),$em, $topics, $this->getUser()->getId());
		$parameters = array(
				"availableStatus" => TaskStatus::getAllStatuses($em),
				"em" => $em,
				"office" => $office,
				"parentTopic" => $parentTopic,
				"breadcrumbs" => $breadcrumbs,
				'officeUsers' => array_map(function($e){return $e->getInArray();}, $officeUsers->toArray()),
				'users' => array_map(function($e){return $e->getInArray();}, $users),
				'offices' => $childrenOfficesForUser,
				'windowtopics' => $data["topics"],
				'tabsId' => $data["tabId"],
				'dataid' => PersonalPage::getAllIdForUser($em, $this->getUser()->getId()),
				"curent" => $curent,
				"officeForWindow" => PersonalPage::getOfficeForWindow($em, $window,$this->getUser()->getId()),
				"window" => $window
		);
		if($parameters["dataid"])
			return $this->render("IntranetMainBundle:PersonalPage:chatPersonalPage.html.twig", $parameters);
		else 
			return;
	}
	
	public function deleteWindowFromPersonalPageAction($windowid)
	{
		$em = $this->getDoctrine()->getManager();
		$windows = $em->getRepository('IntranetMainBundle:PersonalPage')->findByWindowid($windowid);
		foreach ($windows as $window)
		{
			$em->remove($window);
			$em->flush();
		}
		$parameters = PersonalPage::getDataForUser($em, $this->getUser()->getId());
		return $this->render('IntranetMainBundle:PersonalPage:showPersonalPage.html.twig', $parameters);
	}
	
	public function deleteTabFromPersonalPageAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$tab = $em->getRepository('IntranetMainBundle:PersonalPage')->find($id);
		$em->remove($tab);
		$em->flush();
		$parameters = PersonalPage::getDataForUser($em, $this->getUser()->getId());
		return $this->render('IntranetMainBundle:PersonalPage:showPersonalPage.html.twig', $parameters);
	}

}