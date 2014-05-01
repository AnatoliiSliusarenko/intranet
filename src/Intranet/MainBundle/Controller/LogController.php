<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Intranet\MainBundle\Entity\Log;

class LogController extends Controller
{
    public function showLogsAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$loger = $this->get('intranet.loger');
    	$logs = $loger->getAllLogs();
        return $this->render('IntranetMainBundle:Log:showLogs.html.twig', array('logs' => $logs));
    }
}
