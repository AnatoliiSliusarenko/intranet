<?php

namespace Intranet\MainBundle\Services;

use Doctrine\ORM\Mapping as ORM;
use Intranet\MainBundle\Entity\Task;
use Intranet\MainBundle\Entity\User;
use Intranet\MainBundle\Entity\TaskActivityLog;

class TaskReporter
{
	private $user = null;
	private $em = null;
	
    public function __construct($securityContext, $em)
    {
    	$this->user = $securityContext->getToken()->getUser();
    	$this->em = $em;
    }
}
