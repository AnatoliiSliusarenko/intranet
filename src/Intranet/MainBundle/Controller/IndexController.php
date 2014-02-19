<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\TopicSection;

class IndexController extends Controller
{
	public function getTopicSectionsAction()
	{
		$repository = $this->getDoctrine()->getRepository("IntranetMainBundle:TopicSection");
		$topicSections = $repository->findAll();
		
		return $this->render("IntranetMainBundle::mainMenu.html.twig", array("topicSections" => $topicSections));
	}
	
    public function indexAction()
    {
        return $this->render('IntranetMainBundle:Index:index.html.twig');
    }
    
    public function showSectionAction($section_id)
    {
    	$repository = $this->getDoctrine()->getRepository("IntranetMainBundle:TopicSection");
    	$section = $repository->find($section_id);
    	if ($section == null)
    		return $this->redirect($this->generateUrl('intranet_main_homepage'));
    	//fetch topics
    	
    	return $this->render('IntranetMainBundle:Index:showSection.html.twig', array("section" => $section));
    }
}
