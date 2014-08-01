<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskActivityLog
 *
 * @ORM\Table(name="task_activity_logs")
 * @ORM\Entity
 */
class TaskActivityLog 
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="taskActivityLogs")
     * @ORM\JoinColumn(name="userid")
     * @var User
     */
    private $user;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="taskid", type="integer")
     */
    private $taskid;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="resourceid", type="integer")
     */
    private $resourceid;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="loged", type="datetime")
     */
    private $loged;

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
     * @return TaskActivityLog
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
     * Set resourceid
     *
     * @param integer $resourceid
     * @return TaskActivityLog
     */
    public function setResourceid($resourceid)
    {
        $this->resourceid = $resourceid;

        return $this;
    }

    /**
     * Get resourceid
     *
     * @return integer 
     */
    public function getResourceid()
    {
        return $this->resourceid;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return TaskActivityLog
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
     * Set loged
     *
     * @param \DateTime $loged
     * @return TaskActivityLog
     */
    public function setLoged($loged)
    {
        $this->loged = $loged;

        return $this;
    }

    /**
     * Get loged
     *
     * @return \DateTime 
     */
    public function getLoged()
    {
        return $this->loged;
    }

    /**
     * Set user
     *
     * @param \Intranet\MainBundle\Entity\User $user
     * @return TaskActivityLog
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

    /**
     * Set taskid
     *
     * @param integer $taskid
     * @return TaskActivityLog
     */
    public function setTaskid($taskid)
    {
        $this->taskid = $taskid;

        return $this;
    }

    /**
     * Get taskid
     *
     * @return integer 
     */
    public function getTaskid()
    {
        return $this->taskid;
    }
    
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'userid' => $this->getUserid(),
    			'user' => $this->getUser()->getInArray(),
    			'taskid' => $this->getTaskid(),
    			'task' => ($this->task != null) ? $this->task->getInArray() : null,
    			'resourceid' => $this->getResourceid(),
    			'type' => $this->getType(),
    			'loged' => $this->getLoged(),
    			'displayLabel' => ($this->displayLabel != null) ? $this->displayLabel : null
    	);
    }
}
