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
    
    //time period from one status to another
    private function timePeriod($user, $task, $statusFrom, $statusTo, $from, $to)
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
    					$statusFromFinded = false;
    				}
    			}
    				
    			if (($log->getType() == 'user-changed') && ($log->getResourceid() == $user->getId()))
    			{
    				$assigned = true;
    				if ($statusFromFinded) $statusFromDatetime = $log->getLoged();
    			}else
    			if (($log->getType() == 'user-changed') && ($log->getResourceid() != $user->getId()))
    			{
    				$assigned = false;
    			}
    	}
    		
    	$suma = 0;
    	foreach ($values as $value)
    	{
    		$suma = $suma + $value;
    	}
    	return (count($values)>0) ? $suma/count($values) : null;
    }
    
    //spent time on task by user in certain day
    private function spentTimeForDay($task, $user, $date)
    {
    	$qb = $this->em->createQueryBuilder();
    	$qb->select('l')
    	->from('IntranetMainBundle:TaskActivityLog', 'l')
    	->andWhere('l.taskid = :taskid')
    	->setParameter('taskid', $task->getId())
    	->orderBy('l.loged', 'ASC');
    	 
    	$from = clone $date->setTime(0, 0, 0);
    	$to = clone $date->setTime(23, 59, 59);
    	 
    	$qb->andWhere('l.loged > :from')
    	->setParameter('from', $from->format('Y-m-d H:i:s'));
    	 
    	$qb->andWhere('l.loged < :to')
    	->setParameter('to', $to->format('Y-m-d H:i:s'));
    	 
    	$logs = $qb->getQuery()->getResult();
    	 
    	$calculatingStatuses = $this->em->getRepository('IntranetMainBundle:TaskStatus')->findByCalcTimeStart(true);
    	$calculatingStatusesIds = array_map(function($status){return $status->getId();}, $calculatingStatuses);
    	
    	$notCalculatingStatuses = $this->em->getRepository('IntranetMainBundle:TaskStatus')->findByCalcTimeStop(true);
    	$notCalculatingStatusesIds = array_map(function($status){return $status->getId();}, $notCalculatingStatuses);
    	
    	
    	$qb = $this->em->createQueryBuilder();
    	$qb->select('l')
    	->from('IntranetMainBundle:TaskActivityLog', 'l')
    	->andWhere('l.taskid = :taskid')
    	->setParameter('taskid', $task->getId())
    	->andWhere('l.type = :type')
    	->setParameter('type', 'user-changed')
    	->andWhere('l.loged < :loged')
    	->setParameter('loged', $from->format('Y-m-d H:i:s'))
    	->orderBy('l.loged', 'ASC');
    	
    	$assignedLogs = $qb->getQuery()->getResult();
    	
    	$assigned = false;
    	foreach ($assignedLogs as $assignedLog)
    	{
    		if ($assignedLog->getResourceid() == $user->getId())
    		{
    			$assigned = true;
    		}else
    		$assigned = false;
    	}
    	
    	$value = 0;
    	$statusFinded = false;
    	$statusFindedDatetime = null;
    	
    	foreach ($logs as $log)
    	{
    		if (($log->getType() == 'status-changed') && (in_array($log->getResourceid(), $calculatingStatusesIds)))
    		{
    			$statusFinded = true;
    			$statusFindedDatetime = $log->getLoged();
    		} else
    		if (($log->getType() == 'status-changed') && (in_array($log->getResourceid(), $notCalculatingStatusesIds)))
    		{
    			if (($statusFinded) && ($assigned))
    			{
    		
    				$differenceInSeconds = $log->getLoged()->format('U') - $statusFindedDatetime->format('U');
    					
    				$value = $value + $differenceInSeconds;
    			}
    			$statusFinded = false;
    		}
    		
    		if (($log->getType() == 'user-changed') && ($log->getResourceid() == $user->getId()))
    		{
    			$assigned = true;
    			if ($statusFinded) $statusFindedDatetime = $log->getLoged();
    		}else
    		if (($log->getType() == 'user-changed') && ($log->getResourceid() != $user->getId()))
    		{
    			if (($statusFinded) && ($assigned))
    			{
    		
    				$differenceInSeconds = $log->getLoged()->format('U') - $statusFindedDatetime->format('U');
    		
    				$value = $value + $differenceInSeconds;
    			}
    			$assigned = false;
    		}
    	}
    	
    	if (($statusFinded) && ($assigned))
    	{
    		$qb = $this->em->createQueryBuilder();
    		$qb->select('l')
    		->from('IntranetMainBundle:TaskActivityLog', 'l')
    		->andWhere('l.taskid = :taskid')
    		->setParameter('taskid', $task->getId())
    		->andWhere('l.type = :type')
    		->setParameter('type', 'status-changed')
    		->andWhere($qb->expr()->in('l.resourceid', $notCalculatingStatusesIds))
    		->andWhere('l.loged > :loged')
    		->setParameter('loged', $to->format('Y-m-d H:i:s'))
    		->orderBy('l.loged', 'ASC');
    		 
    		$statusLogs = $qb->getQuery()->getResult();
    		
    		
    		
    		
    		
    		
    		
    		
    		$now = new \DateTime();
    		$differenceInSeconds = $now->format('U') - $statusFindedDatetime->format('U');
    	
    		$value = $value + $differenceInSeconds;
    	}
    	
    	return $value;
    }
    
    //spent time on task in status by user
    private function spentTime($task, $status, $user, $from, $to)
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
    	
    	$assigned = false;
    	$value = 0;
    	$statusFinded = false;
    	$statusFindedDatetime = null;
    	
    	foreach ($logs as $log)
    	{
    			if (($log->getType() == 'status-changed') && ($log->getResourceid() == $status->getId()))
    			{
    				$statusFinded = true;
    				$statusFindedDatetime = $log->getLoged();
    			} else
    			if (($log->getType() == 'status-changed') && ($log->getResourceid() != $status->getId()))
    			{
    				if (($statusFinded) && ($assigned))
    				{
    						
    					$differenceInSeconds = $log->getLoged()->format('U') - $statusFindedDatetime->format('U');
    					
    					$value = $value + $differenceInSeconds;
    				}
    				$statusFinded = false;
    			}
    				
    			if (($log->getType() == 'user-changed') && ($log->getResourceid() == $user->getId()))
    			{
    				$assigned = true;
    				if ($statusFinded) $statusFindedDatetime = $log->getLoged();
    			}else 
    			if (($log->getType() == 'user-changed') && ($log->getResourceid() != $user->getId()))
    			{
    				if (($statusFinded) && ($assigned))
    				{
    				
    					$differenceInSeconds = $log->getLoged()->format('U') - $statusFindedDatetime->format('U');
    						
    					$value = $value + $differenceInSeconds;
    				}
    				$assigned = false;
    			}
    	}
    	
    	if (($statusFinded) && ($assigned))
    	{
    		$now = new \DateTime();
    		$differenceInSeconds = $now->format('U') - $statusFindedDatetime->format('U');
    		
    		$value = $value + $differenceInSeconds;
    	}
    	
    	return $value;
    }
    
    private function getTasksByDay($date)
    {
    	$qb = $this->em->createQueryBuilder();
    	$qb->select('l')
    	->from('IntranetMainBundle:TaskActivityLog', 'l')
    	->orderBy('l.loged', 'ASC');
    	
    	$from = clone $date->setTime(0, 0, 0);
    	$to = clone $date->setTime(23, 59, 59);
    	
    	$qb->andWhere('l.loged > :from')
    	->setParameter('from', $from->format('Y-m-d H:i:s'));
    	
    	$qb->andWhere('l.loged < :to')
    	->setParameter('to', $to->format('Y-m-d H:i:s'));
    	
    	$logs = $qb->getQuery()->getResult();
    	
    	$calculatingStatuses = $this->em->getRepository('IntranetMainBundle:TaskStatus')->findByCalcTimeStart(true);
    	$calculatingStatusesIds = array_map(function($status){return $status->getId();}, $calculatingStatuses);
    	
    	$taskIds = array();
    	foreach ($logs as $log)
    	{
    		if (($log->getType() == 'status-changed') && (in_array($log->getResourceid(), $calculatingStatusesIds)))
    		{
    			if (!in_array($log->getTaskid(), $taskIds))
    				$taskIds[] = $log->getTaskid();
    		}
    	}
    	
    	$tasks = array();
    	foreach ($taskIds as $taskId)
    	{
    		$task = $this->em->getRepository('IntranetMainBundle:Task')->find($taskId);
    		if ($task != null)
    		{
    			$tasks[] = $task;
    		}
    	}
    	
    	return $tasks;
    }
    
    private function generateDatetimeInterval($from, $to)
    {
    	if ($from == null)
    	{
    		$query = $this->em->createQuery("SELECT MIN(l.loged) FROM Intranet\MainBundle\Entity\TaskActivityLog l");
    		$from = $query->getResult();
    		$from = $from[0][1];
    	}
    	
    	if ($to == null)
    	{
    		$query = $this->em->createQuery("SELECT MAX(l.loged) FROM Intranet\MainBundle\Entity\TaskActivityLog l");
    		$to = $query->getResult();
    		$to = $to[0][1];
    	}
    	
    	$interval = array();
    	$fromDatetime = new \DateTime($from);
    	$toDatetime = new \DateTime($to);
    	
    	$daysBetween = intval($fromDatetime->diff($toDatetime)->format('%R%a'));
    	if ($daysBetween<0) return $interval;
    	
    	$interval[] = $fromDatetime;
    	$buf = $fromDatetime;
    	
    	for ($i = 0; $i < $daysBetween; $i++) {
    		$buf = clone $buf;
    		$interval[] = $buf->modify('+1 day');
    	}
    	
    	return $interval;
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
    		'rows' => array(),
    		'groupByColumn' => null
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
    						$valueInSeconds = $this->timePeriod($user, $task, $statusFrom, $statusTo, $from, $to);
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
	    								'value' => $this->getTimeString($this->spentTime($task, $status, $user, $from, $to))
	    						);
	    					}
	    					$result['rows'][] = $newRow;
    					}
    				}
    				
    				return $result;
    			}
    		case 'type3':
    			{
    				$result['cols'][] = array('label' => 'Day');
    				$result['cols'][] = array('label' => 'Task');
    				foreach ($users as $user)
    				{
    					$result['cols'][] = array('label' => $user->getName());
    				}
    				
    				$datetimeInterval = $this->generateDatetimeInterval($from, $to);
    				
    				foreach ($datetimeInterval as $date)
    				{
    					$tasksByDay = $this->getTasksByDay($date);
    					
    					foreach ($tasksByDay as $task)
    					{
    						$newRow = array();
    						$newRow[] = array(
    								'value' => $date->format('D d F Y')
    						);
    						$newRow[] = array(
    								'value' => $task->getName()
    						);
    						
    						foreach ($users as $user)
    						{
    							$newRow[] = array(
    									'value' => $this->getTimeString($this->spentTimeForDay($task, $user, $date))
    							);
    						}
    						$result['rows'][] = $newRow;
    					}
    				}
    				
    				$result['groupByColumn'] = 0;
    				return $result;
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
