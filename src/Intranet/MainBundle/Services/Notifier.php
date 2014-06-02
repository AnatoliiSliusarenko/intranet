<?php

namespace Intranet\MainBundle\Services;

use Doctrine\ORM\Mapping as ORM;
use Intranet\MainBundle\Entity\Notification;
use Intranet\MainBundle\Entity\Topic;
use Intranet\MainBundle\Entity\Office;
use Intranet\MainBundle\Entity\User;

class Notifier
{
	private $user = null;
	private $em = null;
	private $router = null;
	private $mailer = null;
	
	private $types = array(
    			"message_office",
    			"message_topic",
    			"membership_own",
    			"membership_own_out",
    			"membership_user",
    			"membership_user_out",
    			"membership_out_own",
    			"membership_out_user",
    			"removed_office",
    			"removed_topic",
    			"topic_added",
    			"task_assigned",
				"task_comment");
	
    public function __construct($securityContext, $em, $router, $mailer)
    {
    	$this->user = $securityContext->getToken()->getUser();
    	$this->em = $em;
    	$this->router = $router;
    	$this->mailer = $mailer;
    }
    
    private function postNotification($user, $type, $destinationid, $message)
    {
    	$notification = new Notification();
    	$notification->setUserid($user->getId());
    	$notification->setUser($user);
    	$notification->setDestinationid($destinationid);
    	$notification->setType($type);
    	$notification->setMessage($message);
    	$notification->setActivated(new \DateTime());
    	$this->em->persist($notification);
    	$this->em->flush();
    	 
    	$this->sendNotificationEmail($user, $message, $type, $destinationid);
    }
    
    private function sendNotificationEmail($user, $message, $type, $destinationid)
    {
    	if (in_array($type, array("message_topic","removed_topic","topic_added")))
    	{
    		$link = $this->router->generate('intranet_show_topic', array('topic_id' => $destinationid), true);
    	}else
    	{
    		$link = $this->router->generate('intranet_show_office', array('office_id' => $destinationid), true);
    	}
    	 
    	$message = $message.
    	"\n\nPlease go to the next link: \n".$link;
    	 
    	$message = \Swift_Message::newInstance()
    	->setSubject('Zectranet notification!')
    	->setFrom('support@zectranet.com')
    	->setTo($user->getEmail())
    	->setBody($message);
    	$this->mailer->send($message);
    }
    
    public function clearNotificationsByOfficeId($office_id)
    {
    	$qb = $this->em->createQueryBuilder();
    	$qb->delete('IntranetMainBundle:Notification', 'n')
    	   ->where("n.userid = :userid")
    	   ->andWhere("n.destinationid = :destinationid")
    	   ->andWhere("n.type = 'message_office'
    	   		    OR n.type = 'membership_own'
    	   		    OR n.type = 'membership_own_out'
    	   		    OR n.type = 'membership_user'
    	   		    OR n.type = 'membership_user_out'
    	   		    OR n.type = 'removed_office'
    	   			OR n.type = 'task_comment'
    	   			OR n.type = 'task_assigned'")
           ->setParameter("userid", $this->user->getId())
           ->setParameter("destinationid", $office_id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    public function clearNotificationsByTopicId($topic_id)
    {
    	$qb = $this->em->createQueryBuilder();
    	$qb->delete('IntranetMainBundle:Notification', 'n')
    	   ->where("n.userid = :userid")
    	   ->andWhere("n.destinationid = :destinationid")
    	   ->andWhere("n.type = 'message_topic'
    			    OR n.type = 'removed_topic'
    			    OR n.type = 'topic_added'")
           ->setParameter("userid", $this->user->getId())
           ->setParameter("destinationid", $topic_id);
    
    	return $qb->getQuery()->getResult();
    }
    
    public function createNotification($type, $resource, $destination)
    {
    	if (!in_array($type, $this->types)) return false;
    	
    	switch ($type)
    	{
    		case "topic_added":
    			{
    				$topicTree = Topic::getTopicTree($em);
    				$rootTopic = $topicTree[0];
    				 
    				$officeTree = Office::getOfficeTree($em);
    				$rootOffice = $officeTree[0];
    				 
    				if ($destination->getParentid() == $rootTopic->getId())
    				{
    					$officeTitle = ($resource->getOffice()->getId() == $rootOffice->getId())
    					? 'Public'
    							:  $resource->getOffice()->getName();
    					$message = 'New topic "'.$resource->getName().'" was added in "'.$officeTitle.'"';
    				}
    				else
    				{
    					$parent = $resource->getParent($em);
    					$message = 'New subtopic "'.$resource->getName().'" was added in "'.$parent->getName().'"';
    				}
    	
    				$users = $destination->getOffice()->getUsers();
    				break;
    			}
    		case "message_office":
    			{
    				$message = 'New message from '.$resource->getName().' '.$resource->getSurname().' in "'.$destination->getName().'"';
    				$users = $destination->getUsers();
    				break;
    			}
    		case "message_topic":
    			{
    				$message = 'New message from '.$resource->getName().' '.$resource->getSurname().' in "'.$destination->getName().'"';
    				$users = $destination->getOffice()->getUsers();
    				break;
    			}
    		case "membership_own":
    			{
    				$message = 'You was added to "'.$destination->getName().'"';
    				$users = $destination->getUsers();
    				break;
    			}
    		case "membership_user_out":
    			{
    				$users = $destination->getUsers();
    				foreach($resource as $userOffice)
    				{
    					if ($userOffice->getId() == $creator->getId()) continue;
    	
    					$message = 'You was deleted from "'.$destination->getName().'"';
    					$type = "membership_own_out";
    					 
    					$this->postNotification($userOffice, $type, $destination->getId(), $message);
    	
    					foreach ($users as $user)
    					{
    						if (($user->getId() == $creator->getId()) || ($user->getId() == $userOffice->getId())) continue;
    						$type = "membership_user_out";
    						$message = $userOffice->getName().' '.$userOffice->getSurname().' was deleted from "'.$destination->getName().'"';
    							
    						$this->postNotification($user, $type, $destination->getId(), $message);
    					}
    				}
    				return true;
    			}
    		case "membership_user":
    			{
    				$users = $destination->getUsers();
    				foreach($resource as $userOffice)
    				{
    					if ($userOffice->getId() == $creator->getId()) continue;
    	
    					$message = 'You was added to "'.$destination->getName().'"';
    					$type = "membership_own";
    					 
    					$this->postNotification($userOffice, $type, $destination->getId(), $message);
    	
    					foreach ($users as $user)
    					{
    						if (($user->getId() == $creator->getId()) || ($user->getId() == $userOffice->getId())) continue;
    						$type = "membership_user";
    						$message = $userOffice->getName().' '.$userOffice->getSurname().' was added to "'.$destination->getName().'"';
    							
    						$this->postNotification($user, $type, $destination->getId(), $message);
    					}
    				}
    				return true;
    			}
    		case "removed_office":
    			{
    				$message = '"'.$destination->getName().'" was delated!';
    				$users = $destination->getUsers();
    				break;
    			}
    		case "removed_topic":
    			{
    				$message = '"'.$destination->getName().'" was delated!';
    				$users = $destination->getOffice()->getUsers();
    				break;
    			}
    	
    		case "task_assigned":
    			{
    				$message = 'You was assigned to the task "'.$resource->getName().'"';
    				$users = array($resource->getUser());
    				break;
    			}
    			
    		case "task_comment":
    			{
    				$message = 'New comment around the task "'.$resource->getName().'"';
    				$users = User::getTaskCommentsMembers($this->em, $resource->getId());
    				$users[] = $resource->getUser();
    				break;
    			}
    	}
    	
    	foreach($users as $user)
    	{
    		if ($user->getId() == $this->user->getId()) continue;
    	
    		$this->postNotification($user, $type, $destination->getId(), $message);
    	}
    	 
    	return true;
    }
}
