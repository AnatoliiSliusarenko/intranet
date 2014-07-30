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
    	'task-created', 
    	'task-topic-assigned',    	
    	'task-estimated',
    	'task-commented'
    );
    
    private function postLog($task, $type, $resourceid = 0)
    {
    	$taskActivityLog = new TaskActivityLog();
    	$taskActivityLog->setUserid($this->user->getId());
    	$taskActivityLog->setUser($this->user);
    	$taskActivityLog->setTaskid($task->getId());
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
    
    public function addChangesLog($newStateOfTask = null, $resource = null)
    {
    	if ($this->oldStateOfTask == null)
    		$this->postLog($newStateOfTask, 'task-created');
    	
    	if (($this->oldStateOfTask == null) 
    		|| ($this->oldStateOfTask->getStatusid() != $newStateOfTask->getStatusid()))
    			$this->postLog($newStateOfTask, 'status-changed', $newStateOfTask->getStatusid());
    	
    	if ((($this->oldStateOfTask == null) && ($newStateOfTask->getUserid() != null)) 
    		|| (($this->oldStateOfTask != null) && ($this->oldStateOfTask->getUserid() != $newStateOfTask->getUserid())))
    			$this->postLog($newStateOfTask, 'user-changed', $newStateOfTask->getUserid());
    	
    	if ((($this->oldStateOfTask == null) && ($newStateOfTask->getEstimated() != null))
    	|| (($this->oldStateOfTask != null) && ($this->oldStateOfTask->getEstimated() != $newStateOfTask->getEstimated())))
    		$this->postLog($newStateOfTask, 'task-estimated', $newStateOfTask->getEstimated());

    	if (($this->oldStateOfTask == null)
    	|| ($this->oldStateOfTask->getTopicid() != $newStateOfTask->getTopicid()))
    		$this->postLog($newStateOfTask, 'task-topic-assigned', $newStateOfTask->getTopicid());
    	
    	$this->oldStateOfTask = null;
    	
    	return true;
    }
    
    public function addCommentLog($task, $post)
    {
    	$this->postLog($task, 'task-commented', $post['id']);
    }
    
    public function getAllLogs($filter, $inArray=false)
    {
    	$qb = $this->em->createQueryBuilder();
    	
    	$qb->select('l')
    	   ->from('IntranetMainBundle:TaskActivityLog', 'l')
    	   ->orderBy('l.id', 'DESC');
    	    	
    	if (isset($filter->tasks) && ($filter->tasks != array()))
    	{
    		$qb->andWhere($qb->expr()->in('l.taskid', $filter->tasks));
    	}
    	
    	if (isset($filter->users) && ($filter->users != array()))
    	{
    		$qb->andWhere($qb->expr()->in('l.userid', $filter->users));
    	}
    	
    	if (isset($filter->types) && ($filter->types != array()))
    	{
    		$qb->andWhere($qb->expr()->in('l.type', $filter->types));
    	}
    	
    	$logs = $qb->getQuery()->getResult();
    	
    	foreach ($logs as $log)
    	{
    		$log->task = $this->em->getRepository('IntranetMainBundle:Task')->find($log->getTaskid());
    		    		
    		switch ($log->getType())
    		{
    			case 'status-changed':{
    				$status = $this->em->getRepository('IntranetMainBundle:TaskStatus')->find($log->getResourceid());
    				$log->displayLabel = ($status != null) ? $status->getLabel() : '-';
    				break;
    			}
    			case 'user-changed': {
    				$user = $this->em->getRepository('IntranetMainBundle:User')->find($log->getResourceid());
    				$log->displayLabel = ($user != null) ? $user->getSurname().' '.$user->getName() : '-';
    				break;
    			}
    			case 'task-commented':{
    				$post = $this->em->getRepository('IntranetMainBundle:PostTask')->find($log->getResourceid());
    				$log->displayLabel = ($post != null) ? $post->getMessage() : '-';
    				break;
    			}
    			case 'task-topic-assigned':{
    				$topic = $this->em->getRepository('IntranetMainBundle:Topic')->find($log->getResourceid());
    				$log->displayLabel = ($topic != null) ? $topic->getName() : '-';
    				break;
    			}
    			case 'task-estimated':{
    				$log->displayLabel = 'Minutes';
    				break;
    			}
    			default: {
    				$log->displayLabel = '';
    			}
    		}
    	}
    	
    	if ($inArray == true)
    		return array_map(function($log){
    			return $log->getInArray();
    		}, $logs);
    	else return $logs;
    }
    
    public function getMyLogs()
    {
    	return $this->user->getLogs();
    }
}
