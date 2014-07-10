<?php

namespace Intranet\MainBundle\Services;

use Doctrine\ORM\Mapping as ORM;
use Intranet\MainBundle\Entity\TaskActivityLog;

class TaskActivityLoger
{
	private $user = null;
	
	private $em = null;
	
	private $oldStateOfTask = null;
	
    private $availableTypes = array(
    	'status-changed',
    	'user-changed',
    	'task-created'
    );
    
    private function postLog($task, $type, $resourceid = null)
    {
    	$taskActivityLog = new TaskActivityLog();
    	$taskActivityLog->setUserid($this->user->getId());
    	$taskActivityLog->setUser($this->user);
    	$taskActivityLog->setTaskid($task->getId());
    	$taskActivityLog->setTask($task);
    	$taskActivityLog->setType($type);
    	$taskActivityLog->setResourceid($resourceid);
    	$taskActivityLog->setLoged(new \DateTime());
    	 
    	$this->em->persist($taskActivityLog);
    	$this->em->flush();
    }
    
    public function __construct($securityContext, $em)
    {
    	$this->user = $securityContext->getToken()->getUser();
    	$this->em = $em;
    }
    
    public function setOldStateOfTask($task)
    {
    	$this->oldStateOfTask = clone $task;
    }
    
    public function addChangesLog($newStateOfTask, $resource = null)
    {
    	if ($this->oldStateOfTask == null)
    		$this->postLog($newStateOfTask, 'task-created');
    	
    	if (($this->oldStateOfTask == null) 
    		|| ($this->oldStateOfTask->getStatusid() != $newStateOfTask->getStatusid()))
    			$this->postLog($newStateOfTask, 'status-changed', $newStateOfTask->getStatusid());
    	
    	if ((($this->oldStateOfTask == null) && ($newStateOfTask->getUserid() != null)) 
    		|| ($this->oldStateOfTask->getUserid() != $newStateOfTask->getUserid()))
    			$this->postLog($newStateOfTask, 'user-changed', $newStateOfTask->getUserid());
    	
    	$this->oldStateOfTask = null;
    	
    	return true;
    }
    
    public function getAllLogs()
    {
    	$logs = $this->em->getRepository('IntranetMainBundle:TaskActivityLog')
    	->createQueryBuilder('l')
    	->select()
    	->orderBy('l.loged', 'DESC')
    	->getQuery()
    	->getResult();
    	
    	array_map(function($log){
    		switch ($log->getType())
    		{
    			case 'status-changed':{
    				$status = $this->em->getRepository('IntranetMainBundle:TaskStatus')->find($log->getResourceid());
    				if ($status != null)
    					$log->resourceLabel = $status->getLabel();
    				break;
    			}
    			case 'user-changed':{
    				if ($log->getResourceid() != null)
    				{
    					$user = $this->em->getRepository('IntranetMainBundle:User')->find($log->getResourceid());
    					if ($user != null)
    						$log->resourceLabel = $user->getSurname().' '.$user->getName();
    				}
    				break;
    			}
    		}
    	}, $logs);
    	
    	return $logs;
    }
    
    public function getMyLogs()
    {
    	return $this->user->getLogs();
    }
}
