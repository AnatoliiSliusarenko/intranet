<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\Task;
use Intranet\MainBundle\Entity\TaskStatus;
use Symfony\Component\HttpFoundation\Response;

class TaskReporterController extends Controller
{
    public function showTaskReporterAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	 
    	$users = $this->getUser()->getAllUsers($em, false, true);
    	$tasks = Task::getAllTasks($em, true);
    	$statuses = TaskStatus::getAllStatuses($em);
    	
        return $this->render('IntranetMainBundle:TaskReporter:showTaskReporter.html.twig', array(
        	'users' => $users,
        	'tasks' => $tasks,
        	'statuses' => $statuses
        ));
    }
    
    public function queryReportAction()
    {
    	$reporter = $this->get('intranet.taskReporter');
    	 
    	$data = json_decode(file_get_contents("php://input"));
    	$filter = (object) $data;
    	 
    	$response = new Response(json_encode(array("result" => $reporter->queryReport($filter))));
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
}
