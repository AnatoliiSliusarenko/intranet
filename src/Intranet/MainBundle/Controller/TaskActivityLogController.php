<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Intranet\MainBundle\Entity\Log;

class TaskActivityLogController extends Controller
{
    public function showTaskActivityLogsAction()
    {
    	$loger = $this->get('intranet.taskActivityLoger');
    	$logs = $loger->getAllLogs();
        return $this->render('IntranetMainBundle:TaskActivityLog:showTaskActivityLogs.html.twig', array('logs' => $logs));
    }
}
