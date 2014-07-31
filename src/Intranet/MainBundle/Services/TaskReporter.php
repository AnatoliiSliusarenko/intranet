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
    
    public function queryReport($filter)
    {
    	switch ($filter->query)
    	{
    		case 'type1':
    			{
    				return 'type1';
    			}
    		case 'type2':
    			{
    				return 'type2';
    			}
    		case 'type3':
    			{
    				return 'type3';
   				}
   			case 'type4':
   				{
   					return 'type4';
   				}
    		default: 
    			{
    				return false;
    			}
    	}
    }
}
