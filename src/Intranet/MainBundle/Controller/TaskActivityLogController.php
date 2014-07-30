<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Intranet\MainBundle\Entity\Log;
use Intranet\MainBundle\Entity\Task;

class TaskActivityLogController extends Controller
{
    public function showTaskActivityLogsAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$users = $this->getUser()->getAllUsers($em, false, true);
    	$tasks = Task::getAllTasks($em, true);
    	
        return $this->render('IntranetMainBundle:TaskActivityLog:showTaskActivityLogs.html.twig', 
        		array('tasks' => $tasks, 'users' => $users));
    }
    
    public function getTaskActivityLogsAction()
    {
    	$loger = $this->get('intranet.taskActivityLoger');
    	
    	$data = json_decode(file_get_contents("php://input"));
    	$filter = (object) $data;
    	
    	$response = new Response(json_encode(array("result" => $loger->getAllLogs($filter, true))));
    	$response->headers->set('Content-Type', 'application/json');
    	return $response;
    }
}
