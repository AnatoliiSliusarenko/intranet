<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TaskReporterController extends Controller
{
    public function showTaskReporterAction()
    {
        return $this->render('IntranetMainBundle:TaskReporter:showTaskReporter.html.twig');
    }
}
