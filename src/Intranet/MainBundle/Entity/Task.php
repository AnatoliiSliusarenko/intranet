<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Intranet\MainBundle\Entity\Notification;
/**
 * Task
 *
 * @ORM\Table(name="tasks")
 * @ORM\Entity
 */
class Task 
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
     * @ORM\Column(name="parentid", type="integer")
     */
    private $parentid;

    /**
     * @var integer
     *
     * @ORM\Column(name="officeid", type="integer")
     */
    private $officeid;
    
    /**
     * @ORM\ManyToOne(targetEntity="Office", cascade={"persist"}, inversedBy="tasks")
     * @ORM\JoinColumn(name="officeid")
     * @var Office
     */
    private $office;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer")
     */
    private $userid;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tasks")
     * @ORM\JoinColumn(name="userid")
     * @var User
     */
    private $user;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="topicid", type="integer")
     */
    private $topicid;
    
    /**
     * @ORM\ManyToOne(targetEntity="Topic",cascade={"persist"}, inversedBy="tasks")
     * @ORM\JoinColumn(name="topicid")
     * @var Topic
     */
    private $topic;
    
    /**
     * @var string
     *
     * @ORM\Column(name="priority", type="text")
     */
    private $priority;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text")
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="statusid", type="integer")
     */
    private $statusid;
    
    /**
     * @ORM\ManyToOne(targetEntity="TaskStatus", inversedBy="tasks")
     * @ORM\JoinColumn(name="statusid")
     * @var TaskStatus
     */
    private $status;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="statusupdated", type="datetime")
     */
    private $statusUpdated;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdate", type="datetime")
     */
    private $startdate;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="estimated", type="integer")
     */
    private $estimated;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enddate", type="datetime")
     */
    private $enddate;
    
    /**
     * @ORM\OneToMany(targetEntity="PostTask", mappedBy="task")
     * @var array
     */
    private $posts;

    /**
     * @var integer
     *
     * @ORM\Column(name="sprintid", type="integer")
     */
    private $sprintid;

    /**
     * @ORM\ManyToOne(targetEntity="Sprint", inversedBy="tasks")
     * @ORM\JoinColumn(name="sprintid")
     * @var Sprint
     */
    private $sprint;

    /**
     * @var integer
     *
     * @ORM\Column(name="ownerid", type="integer")
     */
    private $ownerid;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tasks")
     * @ORM\JoinColumn(name="ownerid")
     * @var User
     */
    private $owner;

    
    private $subTasks = null;
    
    public function getSubTasks($em)
    {
    	if ($this->subTasks == null)
    		$this->subTasks = $em->createQueryBuilder()
    						 ->select('t')
    	   					 ->from('IntranetMainBundle:Task', 't')
    	   					 ->where('t.parentid = :parentid')
    						 ->setParameter('parentid', $this->getId())
    						 ->getQuery()->getResult();
    	
    	return $this->subTasks;
    }
    
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
     * Set officeid
     *
     * @param integer $officeid
     * @return Task
     */
    public function setOfficeid($officeid)
    {
        $this->officeid = $officeid;

        return $this;
    }

    /**
     * Get officeid
     *
     * @return integer 
     */
    public function getOfficeid()
    {
        return $this->officeid;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Task
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set statusid
     *
     * @param integer $statusid
     * @return Task
     */
    public function setStatusid($statusid)
    {
        $this->statusid = $statusid;

        return $this;
    }

    /**
     * Get statusid
     *
     * @return integer 
     */
    public function getStatusid()
    {
        return $this->statusid;
    }

    /**
     * Set startdate
     *
     * @param \DateTime $startdate
     * @return Task
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;

        return $this;
    }

    /**
     * Get startdate
     *
     * @return \DateTime 
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * Set enddate
     *
     * @param \DateTime $enddate
     * @return Task
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;

        return $this;
    }

    /**
     * Get enddate
     *
     * @return \DateTime 
     */
    public function getEnddate()
    {
        return $this->enddate;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->topics = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set priority
     *
     * @param string $priority
     * @return Task
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return string 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set office
     *
     * @param \Intranet\MainBundle\Entity\Office $office
     * @return Task
     */
    public function setOffice(\Intranet\MainBundle\Entity\Office $office = null)
    {
        $this->office = $office;
        return $this;
    }

    /**
     * Get office
     *
     * @return \Intranet\MainBundle\Entity\Office 
     */
    public function getOffice()
    {
        return $this->office;
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
    			'parentid' => $this->getParentid(),
    			'officeid' => $this->getOfficeid(),
    			'userid' => $this->getUserid(),
    			'user' => ($this->getUser() != null ) ? $this->getUser()->getInArray() : null,
    			'topicid' => $this->getTopicid(),
    			'topic' => ($this->getTopic() != null ) ? $this->getTopic()->getInArray() : null,
    			'priority' => $this->getPriority(),
    			'name' => $this->getName(),
    			'description' => $this->getDescription(),
    			'statusid' => $this->getStatusid(),
    			'statusUpdated' => $this->getStatusUpdated(),
    			'status' => $this->getStatus()->getInArray(),
    			'startdate' => $this->getStartdate(),
    			'estimated' => $this->getEstimated(),
    			'enddate' => $this->getEnddate(),
                'sprintid' => $this->getSprintid(),
                'sprint' => ($this->getSprint() != null ) ? $this->getSprint()->getInArray() : null,
                'ownerid' => $this->getOwnerid(),
                'owner' => ($this->getOwner() != null ) ? $this->getOwner()->getInArray() : null
    	);
    }

    /**
     * Add post
     *
     * @param \Intranet\MainBundle\Entity\PostTask $post
     * @return Task
     */
    public function addPost(\Intranet\MainBundle\Entity\PostTask $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Intranet\MainBundle\Entity\PostTask $post
     */
    public function removePost(\Intranet\MainBundle\Entity\PostTask $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set parentid
     *
     * @param integer $parentid
     * @return Task
     */
    public function setParentid($parentid)
    {
        $this->parentid = $parentid;

        return $this;
    }

    /**
     * Get parentid
     *
     * @return integer 
     */
    public function getParentid()
    {
        return $this->parentid;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return Task
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
     * Set user
     *
     * @param \Intranet\MainBundle\Entity\User $user
     * @return Task
     */
    public function setUser(\Intranet\MainBundle\Entity\User $user = null, $notifier)
    {
    	if ((($this->user == null)&&($user != null)) || (($user != null)&&($user->getId() != $this->user->getId())))
    	{
    		$this->user = $user;
    		$notifier->createNotification("task_assigned", $this, $this->getOffice());
    	}
    	
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
    
    public function hasOneOfUsers($em, $usersIds)
    {
    	foreach ($usersIds as $userId)
    	{
    		if ($this->getUserid() == $userId) return true;
    	}
    
    	return false;
    }

    /**
     * Set status
     *
     * @param \Intranet\MainBundle\Entity\TaskStatus $status
     * @return Task
     */
    public function setStatus(\Intranet\MainBundle\Entity\TaskStatus $status = null)
    {
    	if ($status !== $this->status) $this->statusUpdated = new \DateTime();
        $this->status = $status;
        
        if ($status->getInitStartdate() && $this->startdate == null) $this->startdate = new \DateTime();
		if ($status->getUpdateEnddate()) $this->enddate = new \DateTime();
        
        return $this;
    }

    /**
     * Get status
     *
     * @return \Intranet\MainBundle\Entity\TaskStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getAvailableStatus($user)
    {
    	$transitions = $this->getStatus()->getFromTransitions();
    	$statuses =  array_map(function($t){return $t->getToTaskStatus();}, $transitions->toArray());
		
    	$result = array($this->getStatus());
    	foreach ($statuses as $status)
    	{
    		if ($status->isAllowedFor($user)) array_push($result, $status);
    	}
		return $result;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set estimated
     *
     * @param integer $estimated
     * @return Task
     */
    public function setEstimated($estimated)
    {
        $this->estimated = $estimated;

        return $this;
    }

    /**
     * Get estimated
     *
     * @return integer 
     */
    public function getEstimated()
    {
        return $this->estimated;
    }

    /**
     * Set statusUpdated
     *
     * @param \DateTime $statusUpdated
     * @return Task
     */
    public function setStatusUpdated($statusUpdated)
    {
        $this->statusUpdated = $statusUpdated;

        return $this;
    }

    /**
     * Get statusUpdated
     *
     * @return \DateTime 
     */
    public function getStatusUpdated()
    {
        return $this->statusUpdated;
    }
    
    /**
     * Set topicid
     *
     * @param integer $topicid
     * @return Task
     */
    public function setTopicid($topicid)
    {
        $this->topicid = $topicid;

        return $this;
    }

    /**
     * Get topicid
     *
     * @return integer 
     */
    public function getTopicid()
    {
        return $this->topicid;
    }

    /**
     * Set topic
     *
     * @param \Intranet\MainBundle\Entity\Topic $topic
     * @return Task
     */
    public function setTopic(\Intranet\MainBundle\Entity\Topic $topic = null)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return \Intranet\MainBundle\Entity\Topic 
     */
    public function getTopic()
    {
        return $this->topic;
    }
    
    public function oneOfTopics($em, $topicsIds)
    {
    	foreach ($topicsIds as $topicId)
    		if ($this->getTopicid() == $topicId) return true;
    
    	return false;
    }
    
    public static function getAllTasks($em, $inArray=false)
    {
    	$tasks = $em->createQueryBuilder()
    			  ->select('t')
    			  ->from('IntranetMainBundle:Task', 't')
    			  ->orderBy('t.name', 'ASC')
    			  ->getQuery()->getResult();
    	
    	if ($inArray == true)
    		return array_map(function($task){
    			return $task->getInArray();
    		}, $tasks);
    	else return $tasks;
    }

    public function getUserStory()
    {
        $rest = $this->getName();
        if(strlen($rest)< 100)
            return $rest;
        $rest = substr($rest, 0, 97);
        return $rest."...";
    }

    /**
     * Set sprintid
     *
     * @param integer $sprintid
     * @return Task
     */
    public function setSprintid($sprintid)
    {
        $this->sprintid = $sprintid;

        return $this;
    }

    /**
     * Get sprintid
     *
     * @return integer
     */
    public function getSprintid()
    {
        return $this->sprintid;
    }

    public function getParent($em)
    {
        $pid = $this->getParentid();
        if(!$pid)
        {
            $topic = $this->getTopic();
            if($topic)
                return $topic->getName();
            $office = $this->getOffice();
            if($office)
                return $office->getName();
        }
        $parent = $em->getRepository('IntranetMainBundle:Task')->find($pid);
        return $parent->getId();

    }

    public function dateIntervalToString(\DateInterval $interval) {

        // Reading all non-zero date parts.
        $date = array_filter(array(
            ' year ' => $interval->y,
            ' month ' => $interval->m,
            ' days ' => $interval->d
        ));

        // Reading all non-zero time parts.
        $time = array_filter(array(
            ' hrs ' => $interval->h,
            ' mins ' => $interval->i,
            ' sec ' => $interval->s
        ));

        $specString = '';

        // Adding each part to the spec-string.
        foreach ($date as $key => $value) {
            $specString .= $value . $key;
        }
        if (count($time) != 0) {
            foreach ($time as $key => $value) {
                $specString .= $value . $key;
            }
        }

        return $specString;
    }

    public function getTimeSpent1()
    {
        $status = $this->getStatus()->getLabel();
        if($status == 'In-progress: coding' || $status == 'In-progress: testing'
            || $status == 'In-progress: research')
        {
            $start = $this->getStatusUpdated();
            $now = new \DateTime();
            $spent =$now->diff($start);
            return $spent;
        }
        else
            return  0;
    }

    public function getTimeSpent()
    {
        $status = $this->getStatus()->getLabel();
        if($status == 'In-progress: coding' || $status == 'In-progress: testing'
            || $status == 'In-progress: research')
        {
            $start = $this->getStatusUpdated();
            $now = new \DateTime();
            $spent =$now->diff($start);
            return $this->dateIntervalToString($spent);
        }
        else
            return  0;
    }

    public function getRemaining()
    {
        $status = $this->getStatus()->getLabel();
        if($status == 'In-progress: coding' || $status == 'In-progress: testing'
            || $status == 'In-progress: research')
        {
            $start = $this->getEstimated();
            $now = $this->getTimeSpent1();
            $mins = $now->m*30*24*60 + $now->d*24*60 + $now->i;
            $remaning = $start-$mins;
            if($remaning < 0)
                return "TIME OUT!";
            if($remaning == 0)
                return 0;
            $min = $remaning%60;
            $hrs = ($remaning-$min)/60;
            if($hrs != 0 && $min != 0)
                return $hrs." hrs ".$min." mins";
            if($min == 0)
                return $hrs." hrs ";
            if($hrs == 0)
                return $min." mins";
        }
        else
        {
            if($this->getEstimated() == 0)
                return 0;
            $min = $this->getEstimated()%60;
            $hrs = ($this->getEstimated()-$min)/60;
            if($hrs != 0 && $min != 0)
                return $hrs." hrs ".$min." mins";
            if($min == 0)
                return $hrs." hrs ";
            if($hrs == 0)
                return $min." mins";
        }

    }


    /**
     * Set ownerid
     *
     * @param integer $ownerid
     * @return Task
     */
    public function setOwnerid($ownerid)
    {
        $this->ownerid = $ownerid;

        return $this;
    }

    /**
     * Get ownerid
     *
     * @return integer 
     */
    public function getOwnerid()
    {
        return $this->ownerid;
    }

    /**
     * Set sprint
     *
     * @param \Intranet\MainBundle\Entity\Sprint $sprint
     * @return Task
     */
    public function setSprint(\Intranet\MainBundle\Entity\Sprint $sprint = null)
    {
        $this->sprint = $sprint;

        return $this;
    }

    /**
     * Get sprint
     *
     * @return \Intranet\MainBundle\Entity\Sprint 
     */
    public function getSprint()
    {
        return $this->sprint;
    }

    /**
     * Set owner
     *
     * @param \Intranet\MainBundle\Entity\User $owner
     * @return Task
     */
    public function setOwner(\Intranet\MainBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Intranet\MainBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
