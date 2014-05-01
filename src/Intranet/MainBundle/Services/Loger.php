<?php

namespace Intranet\MainBundle\Services;

use Doctrine\ORM\Mapping as ORM;
use Intranet\MainBundle\Entity\Log;

class Loger
{
	private $user = null;
	
	private $em = null;
	
    private $availableTypes = array(
    	'task-discussion-start',
    	'task-discussion-finished',
    	'task-specification-start',
    	'task-specification-finished',
    	'task-specification-approved',
    	'task-assigned',
    	'task-opened',
    	'task-inprogress-coding',
    	'task-inprogress-testing',
    	'task-inprogress-research',
    	'task-onhold-lunch',
    	'task-onhold-home',
    	'task-onhold-meeting',
    	'task-onhold-suspended',
    	'task-resolved',
    	'task-resolved-approved',
    	'task-closed',
    	'task-reopened'
    );
    
    public function __construct($securityContext, $em)
    {
    	$this->user = $securityContext->getToken()->getUser();
    	$this->em = $em;
    }
    
    public function addLog($type, $resourceid, $destinationid)
    {
    	if (!in_array($type, $this->availableTypes)) return null;
    	
    	$log = new Log();
    	$log->setuserid($this->user->getId());
    	$log->setUser($this->user);
    	$log->setType($type);
    	$log->setResourceid($resourceid);
    	$log->setDestinationid($destinationid);
    	$log->setLoged(new \DateTime());
    	
    	$this->em->persist($log);
    	$this->em->flush();
    	
    	return $log;
    }
    
    public function getAllLogs()
    {
    	return $this->em->getRepository('IntranetMainBundle:Log')
    	->createQueryBuilder('l')
    	->select()
    	->orderBy('l.loged', 'DESC')
    	->getQuery()
    	->getResult();
    }
    
    public function getMyLogs()
    {
    	return $this->user->getLogs();
    }
}
