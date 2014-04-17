<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="officeid", type="integer")
     */
    private $officeid;
    
    /**
     * @ORM\ManyToOne(targetEntity="Office", inversedBy="tasks")
     * @ORM\JoinColumn(name="officeid")
     * @var Office
     */
    private $office;
    
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
     * @ORM\Column(name="status", type="text")
     */
    private $status;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdate", type="datetime")
     */
    private $startdate;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enddate", type="datetime")
     */
    private $enddate;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="tasks")
     * @var array
     */
    private $users;
    
    /**
     * @ORM\ManyToMany(targetEntity="Topic", mappedBy="tasks")
     * @var array
     */
    private $topics;

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
     * Set status
     *
     * @param string $status
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;
        
        if ($status == 'opened')
        {
        	if ($this->startdate == null)
        		$this->startdate = new \DateTime();
        }
        elseif ($status == 'closed')
        	$this->enddate = new \DateTime();

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
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
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->topics = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \Intranet\MainBundle\Entity\User $user
     * @return Task
     */
    public function addUser(\Intranet\MainBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }
    
    public function addUsers($em, $users)
    {
    	$usersAdded = array();
    	foreach ($users as $userid)
    	{
    		$user = $em->getRepository('IntranetMainBundle:User')->find($userid);
    		if ($user == null)
    			continue;
    
    		$usersAdded[] = $user;
    		$this->addUser($user);
    		$user->addTask($this);
    		$em->persist($user);
    	}
    
    	return $usersAdded;
    }

    /**
     * Remove user
     *
     * @param \Intranet\MainBundle\Entity\User $user
     */
    public function removeUser(\Intranet\MainBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
    
    public function resetUsers($em, $users)
    {
    	$existUsersIds = array_map(function($u){return $u->getId();}, $this->users->toArray());
    	$usersToAdd = array();
    	$usersToRemove = array();
    	 
    	foreach ($this->users as $user)
    	{
    		if (!in_array($user->getId(), $users)) $usersToRemove[] = $user;
    	}
    	foreach ($users as $userId)
    	{
    		if (!in_array($userId, $existUsersIds)) $usersToAdd[] = $userId;
    	}
    	 
    	foreach ($usersToRemove as $user)
    	{
    		$user->removeTask($this);
    		$this->removeUser($user);
    		$em->persist($user);
    	}
    	 
    	return  array("added" => $this->addUsers($em, $usersToAdd), "removed" => $usersToRemove);
    }
    
    public function hasUser(\Intranet\MainBundle\Entity\User $user)
    {
    	foreach ($this->users as $curUser)
    	{
    		if ($curUser->getId() == $user->getId()) return true;
    	}
    	 
    	return false;
    }

    /**
     * Add topic
     *
     * @param \Intranet\MainBundle\Entity\Topic $topic
     * @return Task
     */
    public function addTopic(\Intranet\MainBundle\Entity\Topic $topic)
    {
        $this->topics[] = $topic;

        return $this;
    }

    public function addTopics($em, $topics)
    {
    	$topicsAdded = array();
    	foreach ($topics as $topicid)
    	{
    		$topic = $em->getRepository('IntranetMainBundle:Topic')->find($topicid);
    		if ($topic == null)
    			continue;
    
    		$topicsAdded[] = $topic;
    		$this->addTopic($topic);
    		$topic->addTask($this);
    		$em->persist($topic);
    	}
    
    	return $topicsAdded;
    }
    
    /**
     * Remove topic
     *
     * @param \Intranet\MainBundle\Entity\Topic $topic
     */
    public function removeTopic(\Intranet\MainBundle\Entity\Topic $topic)
    {
        $this->topics->removeElement($topic);
    }

    /**
     * Get topics
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTopics()
    {
        return $this->topics;
    }
    
    public function resetTopics($em, $topics)
    {
    	$existTopicsIds = array_map(function($t){return $t->getId();}, $this->topics->toArray());
    	$topicsToAdd = array();
    	$topicsToRemove = array();
    
    	foreach ($this->topics as $topic)
    	{
    		if (!in_array($topic->getId(), $topics)) $topicsToRemove[] = $topic;
    	}
    	foreach ($topics as $topicId)
    	{
    		if (!in_array($topicId, $existTopicsIds)) $topicsToAdd[] = $topicId;
    	}
    
    	foreach ($topicsToRemove as $topic)
    	{
    		$topic->removeTask($this);
    		$this->removeTopic($topic);
    		$em->persist($topic);
    	}
    
    	return  array("added" => $this->addTopics($em, $topicsToAdd), "removed" => $topicsToRemove);
    }
    
    public function hasTopic(\Intranet\MainBundle\Entity\Topic $topic)
    {
    	foreach ($this->topics as $curTopic)
    	{
    		if ($curTopic->getId() == $topic->getId()) return true;
    	}
    	
    	return false;
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
    			'officeid' => $this->getOfficeid(),
    			'priority' => $this->getPriority(),
    			'name' => $this->getName(),
    			'status' => $this->getStatus(),
    			'startdate' => $this->getStartdate(),
    			'enddate' => $this->getEnddate(),
    			'users' => array_map(function($u){return $u->getInArray();}, $this->getUsers()->toArray()),
    			'topics' => array_map(function($t){return $t->getInArray();}, $this->getTopics()->toArray())
    	);
    }
}
