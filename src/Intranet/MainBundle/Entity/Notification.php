<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notifications")
 * @ORM\Entity
 */
class Notification 
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer")
     */
    private $userid;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="notifications")
     * @ORM\JoinColumn(name="userid")
     * @var User
     */
    private $user;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="destinationid", type="integer")
     */
    private $destinationid;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="activated", type="datetime")
     */
    private $activated;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return Notification
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return integer 
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set destinationid
     *
     * @param integer $destinationid
     * @return Notification
     */
    public function setDestinationid($destinationid)
    {
        $this->destinationid = $destinationid;

        return $this;
    }

    /**
     * Get destinationid
     *
     * @return integer 
     */
    public function getDestinationid()
    {
        return $this->destinationid;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Notification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set user
     *
     * @param \Intranet\MainBundle\Entity\User $user
     * @return Notification
     */
    public function setUser(\Intranet\MainBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Intranet\MainBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    
    private static function postNotification($em, $mailer, $user, $type, $destinationid, $message)
    {
    	$notification = new Notification();
    	$notification->setUserid($user->getId());
    	$notification->setUser($user);
    	$notification->setDestinationid($destinationid);
    	$notification->setType($type);
    	$notification->setMessage($message);
    	$notification->setActivated(new \DateTime());
    	$em->persist($notification);
    	$em->flush();
    	
    	self::sendNotificationEmail($mailer, $user, $message);
    }
    
    public static function createNotification($em, $mailer, $creator, $type, $resource, $destination)
    {
    	$types = array(
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
    			"topic_added");
    	
    	if (!in_array($type, $types)) return false;
    	
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
    			
    				self::postNotification($em, $mailer, $userOffice, $type, $destination->getId(), $message);
    				    				
    				foreach ($users as $user)
    				{
    					if (($user->getId() == $creator->getId()) || ($user->getId() == $userOffice->getId())) continue;
    					$type = "membership_user_out";
    					$message = $userOffice->getName().' '.$userOffice->getSurname().' was deleted from "'.$destination->getName().'"';
    					
    					self::postNotification($em, $mailer, $user, $type, $destination->getId(), $message);
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
    			
    				self::postNotification($em, $mailer, $userOffice, $type, $destination->getId(), $message);
    				    				
    				foreach ($users as $user)
    				{
    					if (($user->getId() == $creator->getId()) || ($user->getId() == $userOffice->getId())) continue;
    					$type = "membership_user";
    					$message = $userOffice->getName().' '.$userOffice->getSurname().' was added to "'.$destination->getName().'"';
    					
    					self::postNotification($em, $mailer, $user, $type, $destination->getId(), $message);
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
    	}
    	
    	foreach($users as $user)
    	{
    		if ($user->getId() == $creator->getId()) continue;
    		
    		self::postNotification($em, $mailer, $user, $type, $destination->getId(), $message);
    	}
    	
    	return true;
    }
    
    public static function sendNotificationEmail($mailer, $user, $message)
    {
    	$message = \Swift_Message::newInstance()
			    	->setSubject('AmploIntranet notification!')
			    	->setFrom('support@amplointranet.com')
			    	->setTo($user->getEmail())
			    	->setBody($message);
    	$mailer->send($message);
    }
    
    public static function clearNotificationsByOfficeId($em, $user, $office_id)
    {
    	$qb = $em->createQueryBuilder();
    	$qb->delete('IntranetMainBundle:Notification', 'n')
    	   ->where("n.userid = :userid")
    	   ->andWhere("n.destinationid = :destinationid")
    	   ->andWhere("n.type = 'message_office' 
    	   		    OR n.type = 'membership_own' 
    	   		    OR n.type = 'membership_own_out'
    	   		    OR n.type = 'membership_user' 
    	   		    OR n.type = 'membership_user_out'
    	   		    OR n.type = 'removed_office'")
    	   ->setParameter("userid", $user->getId())
    	   ->setParameter("destinationid", $office_id);
    	
    	return $qb->getQuery()->getResult();
    }
    
    public static function clearNotificationsByTopicId($em, $user, $topic_id)
    {
    	$qb = $em->createQueryBuilder();
    	$qb->delete('IntranetMainBundle:Notification', 'n')
    	->where("n.userid = :userid")
    	->andWhere("n.destinationid = :destinationid")
    	->andWhere("n.type = 'message_topic' 
    			 OR n.type = 'removed_topic'
    			 OR n.type = 'topic_added'")
    	->setParameter("userid", $user->getId())
    	->setParameter("destinationid", $topic_id);
    	 
    	return $qb->getQuery()->getResult();
    }
    
    /**
     * Set activated
     *
     * @param \DateTime $activated
     * @return Notification
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * Get activated
     *
     * @return \DateTime 
     */
    public function getActivated()
    {
        return $this->activated;
    }
    
    /**
     * Get inArray
     *
     * @return array
     */
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'user' => $this->getUser()->getInArray(),
    			'destinationid' => $this->getDestinationid(),
    			'type' => $this->getType(),
    			'message' => $this->getMessage(),
    			'activated' => $this->getActivated()
    	);
    }
}
