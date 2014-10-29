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
				"task_comment",
				"private_message_office",
				"private_message_topic",
				"private_message_task");
	
    public function __construct($securityContext, $em, $router, $mailer)
    {
    	$this->user = $securityContext->getToken()->getUser();
    	$this->em = $em;
    	$this->router = $router;
    	$this->mailer = $mailer;
    }
    
    private function postNotification($user, $type, $resourceid, $destinationid, $message)
    {
    	$notification = new Notification();
    	$notification->setUserid($user->getId());
    	$notification->setUser($user);
    	$notification->setResourceid($resourceid);
    	$notification->setDestinationid($destinationid);
    	$notification->setType($type);
    	$notification->setMessage($message);
    	$notification->setActivated(new \DateTime());
    	$this->em->persist($notification);
    	$this->em->flush();
    	$user_settings = $user->getUserSettings();
    	$user_settings_notifications = $user->getUserSettingsNotifications();
    	
    	switch ($type){
    		case "message_office":
    			$method = $user_settings_notifications->getMsgEmailMessageOffice();
    			break;
    		case "message_topic" :
    			$method = $user_settings_notifications->getMsgEmailMessageTopic();
    			break;
    		case "membership_own" :
    			$method = $user_settings_notifications->getMsgEmailMembershipOwn();
    			break;
    		case "membership_own_out" :
    			$method = $user_settings_notifications->getMsgEmailMembershipOwnOut();
    			break;
    		case "membership_user" :
    			$method = $user_settings_notifications->getMsgEmailMembershipUser();
    			break;
    		case "membership_user_out" :
    			$method = $user_settings_notifications->getMsgEmailMembershipUserOut();
    			break;
    		case "membership_out_own" :
    			$method = $user_settings_notifications->getMsgEmailMembershipOwnOut();
    			break;
    		case "membership_out_user" :
    			$method = $user_settings_notifications->getMsgEmailMembershipUserOut();
    			break;
    		case "removed_office" :
    			$method = $user_settings_notifications->getMsgEmailRemovedOffice();
    			break;
    		case "removed_topic" :
    			$method = $user_settings_notifications->getMsgEmailRemovedTopic();
    			break;
    		case "topic_added" :
    			$method = $user_settings_notifications->getMsgEmailTopicAdd();
    			break;
    		case "task_assigned" :
    			$method = $user_settings_notifications->getMsgEmailTaskAssigned();
    			break;
    		case "task_comment" :
    			$method = $user_settings_notifications->getMsgEmailTaskComment();
    			break;
    		case "private_message_office" :
    			$method = "no_method";
    			break;
    		case "private_message_topic" :
    			$method = "no_method";
    			break;
  			case "private_message_task" :
   				$method = "no_method";
   				break;
    	}
    	
    	if($method == "no_method"){
    		$this->sendNotificationEmail($user, $message, $type, $destinationid);
    	}else {
    		if($user_settings->getDisableAllOnEmail() == false ){
    			if($method == true ){
    				$this->sendNotificationEmail($user, $message, $type, $destinationid);
    			}
    		}
    	}
    	
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
    	   			OR n.type = 'task_assigned'
    	   			OR n.type = 'private_message_office'")
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
    			    OR n.type = 'topic_added'
    	   			OR n.type = 'private_message_topic'")
           ->setParameter("userid", $this->user->getId())
           ->setParameter("destinationid", $topic_id);
    
    	return $qb->getQuery()->getResult();
    }
    
    public function clearNotificationsByTaskId($taskId)
    {
    	$qb = $this->em->createQueryBuilder();
    	$qb->delete('IntranetMainBundle:Notification', 'n')
    	->where("n.userid = :userid")
    	->andWhere("n.resourceid = :resourceid")
    	->andWhere("n.type = 'task_comment'
    				OR n.type = 'private_message_task'")
               ->setParameter("userid", $this->user->getId())
               ->setParameter("resourceid", $taskId);
    
    	return $qb->getQuery()->getResult();
    }
    
    public function createNotification($type, $resource, $destination, $user_to_send_name)
    {
    	if (!in_array($type, $this->types)) return false;
    	$creator = $this->user;
    	switch ($type)
    	{
    		case "topic_added":
    			{
    				$topicTree = Topic::getTopicTree($this->em);
    				$rootTopic = $topicTree[0];
    				 
    				$officeTree = Office::getOfficeTree($this->em);
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
    					$parent = $resource->getParent($this->em);
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
    					 
    					$this->postNotification($userOffice, $type, $userOffice->getId(), $destination->getId(), $message);
    	
    					foreach ($users as $user)
    					{
    						if (($user->getId() == $creator->getId()) || ($user->getId() == $userOffice->getId())) continue;
    						$type = "membership_user_out";
    						$message = $userOffice->getName().' '.$userOffice->getSurname().' was deleted from "'.$destination->getName().'"';
    							
    						$this->postNotification($user, $type, $userOffice->getId(), $destination->getId(), $message);
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
    					 
    					$this->postNotification($userOffice, $type, $userOffice->getId(), $destination->getId(), $message);
    	
    					foreach ($users as $user)
    					{
    						if (($user->getId() == $creator->getId()) || ($user->getId() == $userOffice->getId())) continue;
    						$type = "membership_user";
    						$message = $userOffice->getName().' '.$userOffice->getSurname().' was added to "'.$destination->getName().'"';
    							
    						$this->postNotification($user, $type, $userOffice->getId(), $destination->getId(), $message);
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
    				$userAsigned = $resource->getUser();
    				if ($userAsigned != null) $users[] = $userAsigned;
    				break;
    			}
    			
    		case "private_message_office":
    			{
    				$message = 'New message from '.$resource->getName().' '.$resource->getSurname().' in "'.$destination->getName().'"';
    				$users = User::getUserByUsername($this->em, $user_to_send_name);
    				break;
    			}
    		case "private_message_topic":
    			{
    				$message = 'New message from '.$resource->getName().' '.$resource->getSurname().' in "'.$destination->getName().'"';
    				$users = User::getUserByUsername($this->em, $user_to_send_name);
    				break;
    			}
    		case "private_message_task":
    			{
    				$message = 'New message from '.$resource->getName().' '.$resource->getSurname().' in "'.$destination->getName().'"';
    				$users = User::getUserByUsername($this->em, $user_to_send_name);
    				break;
    			}
    	}
    	
    	foreach($users as $user)
    	{
    		if ($user->getId() == $this->user->getId()){
    			$this->postNotification($user, $type, $resource->getId(), $destination->getId(), $message);
    		}
    	}
    	return true;
    }

}
