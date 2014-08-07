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
    
    //time spend from one status to another
    private function calculateTS($user, $task, $statusFrom, $statusTo, $from, $to)
    {
    	$qb = $this->em->createQueryBuilder();
    	$qb->select('l')
    	   ->from('IntranetMainBundle:TaskActivityLog', 'l')
    	   ->andWhere('l.taskid = :taskid')
    	   ->setParameter('taskid', $task->getId())
    	   ->orderBy('l.loged', 'ASC');
    	
    	if ($from != null)
    	{
    		$qb->andWhere('l.loged > :from')
    		->setParameter('from', $from);
    	}
    	 
    	if ($to != null)
    	{
    		$qb->andWhere('l.loged < :to')
    		->setParameter('to', $to);
    	}
    	
    	$logs = $qb->getQuery()->getResult();
    	
    	$values = array();
    		
    	$assigned = false;
    	$statusFromFinded = false;
    	$statusToFinded = false;
    		
    	$statusFromDatetime = null;
    	$statusToDatetime = null;
    		
    	foreach ($logs as $log)
    	{
    		if ($log->getTaskid() == $task->getId())
    		{
    			if (($log->getType() == 'status-changed') && ($log->getResourceid() == $statusFrom->getId()))
    			{
    				$statusFromFinded = true;
    				$statusFromDatetime = $log->getLoged();
    			} else
    			if (($log->getType() == 'status-changed') && ($log->getResourceid() == $statusTo->getId()))
    			{
    				if (($statusFromFinded) && ($assigned))
    				{
    						
    					$differenceInSeconds = $log->getLoged()->format('U') - $statusFromDatetime->format('U');
    					
    					$values[] = $differenceInSeconds;
    				}
    			}
    				
    			if (($log->getType() == 'user-changed') && ($log->getResourceid() == $user->getId()))
    			{
    				$assigned = true;
    				if ($statusFromFinded) $statusFromDatetime = $log->getLoged();
    			}
    		}
    	}
    		
    	$suma = 0;
    	foreach ($values as $value)
    	{
    		$suma = $suma + $value;
    	}
    	return (count($values)>0) ? $suma/count($values) : null;
    }
    
    //spend time on task in status by user
    private function spendTime($task, $status, $user)
    {
    	return '-';
    }
    
    private function getTimeString($seconds)
    {
    	if ($seconds == null) return '-';
    	$differenceDays = floor($seconds/(60*60*24));
    		
    	$differenceHrs =  floor(($seconds%(60*60*24))/(60*60));
    		
    	$differenceMinutes = floor(($seconds%(60*60))/60);
    		
    	$differenceSeconds = $seconds%60;
    	
    	return $differenceDays.' days '.$differenceHrs.' hrs '.$differenceMinutes.' m '.$differenceSeconds.' s';
    }
    
    public function queryReport($filter)
    {	
    	$result = array(
    		'cols' => array(),
    		'rows' => array()
    	);	
    	
    	if (isset($filter->from) && (trim($filter->from) != ''))
    	{
    		$from = $filter->from;
    	}else $from = null;
    	
    	if (isset($filter->to) && (trim($filter->to) != ''))
    	{
    		$to = $filter->to;
    	}else $to = null;
    	
    	$statusFrom = (isset($filter->statusFrom)) ? $this->em->getRepository('IntranetMainBundle:TaskStatus')->find($filter->statusFrom) : null;
    	$statusTo = (isset($filter->statusTo)) ? $this->em->getRepository('IntranetMainBundle:TaskStatus')->find($filter->statusTo) : null;
    	
    	$users = array();
    	if (isset($filter->users) && ($filter->users != array()))
    	{
    		foreach ($filter->users as $userId)
    		{
    			$user = $this->em->getRepository('IntranetMainBundle:User')->find($userId);
    			if ($user != null) $users[] = $user;
    		}
    	}else
    	{
    		$users = $this->em->getRepository('IntranetMainBundle:User')->findAll();
    	}
    	
    	$tasks = array();
    	$tasksAll = false;
    	if (isset($filter->tasks) && ($filter->tasks != array()))
    	{
    		foreach ($filter->tasks as $taskId)
    		{
    			$task = $this->em->getRepository('IntranetMainBundle:Task')->find($taskId);
    			if ($task != null) $tasks[] = $task;
    		}
    	}else
    	{
    		$tasks = Task::getAllTasks($this->em);
    		$tasksAll = true;
    	}
    	
    	$statuses = array();
    	if (isset($filter->statuses) && ($filter->statuses != array()))
    	{
    		foreach ($filter->statuses as $statusId)
    		{
    			$status = $this->em->getRepository('IntranetMainBundle:TaskStatus')->find($statusId);
    			if ($status != null) $statuses[] = $status;
    		}
    	}else
    	{
    		$statuses = $this->em->getRepository('IntranetMainBundle:TaskStatus')->findAll();
    	}
    	
    	switch ($filter->query)
    	{
    		case 'type1':
    			{
    				if (($statusFrom == null) || ($statusTo == null)) return;
    				
    				$result['cols'][] = array('label' => 'User');
    				if (!$tasksAll)
    				{
    					foreach ($tasks as $task)
    					{
    						$result['cols'][] = array(
    								'label' => $task->getName()
    						);
    					}
    				}
    				$result['cols'][] = array('label' => 'Average');
    				
    				foreach ($users as $user)
    				{
    					$newRow = array();
    					$newRow[] = array(
    						'value' => $user->getName()
    					);
    					$values = array();
    					foreach ($tasks as $task)
    					{
    						$valueInSeconds = $this->calculateTS($user, $task, $statusFrom, $statusTo, $from, $to);
    						if ($valueInSeconds != null) $values[] = $valueInSeconds;
    						if (!$tasksAll)
    						$newRow[] = array(
    							'value' =>$this->getTimeString($valueInSeconds)
    						);
    					}
    					$suma = 0;
    					foreach ($values as $value)
    					{
    						$suma = $suma + $value;
    					}
    					$average = (count($values)>0) ? $suma/count($values) : null;
    					
    					
    					$newRow[] = array(
    							'value' => $this->getTimeString($average)
    					);
    					
    					$result['rows'][] = $newRow;
    				}
    				
    				return $result;
    			}
    		case 'type2':
    			{
    				$result['cols'][] = array('label' => 'ID');
    				$result['cols'][] = array('label' => 'Task');
    				$result['cols'][] = array('label' => 'Status');
    				
    				foreach ($users as $user)
    				{
    					$result['cols'][] = array('label' => $user->getName());
    				}
    				
    				foreach ($tasks as $task)
    				{
    					foreach ($statuses as $status)
    					{
    						$newRow = array();
    						$newRow[] = array(
    								'value' => $task->getId()
    						);
    						$newRow[] = array(
    								'value' => $task->getName()
    						);
    						$newRow[] = array(
    								'value' => $status->getLabel()
    						);
	    					foreach ($users as $user)
	    					{
	    						$newRow[] = array(
	    								'value' => $this->spendTime($task, $status, $user)
	    						);
	    					}
	    					$result['rows'][] = $newRow;
    					}
    				}
    				
    				return $result;
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
