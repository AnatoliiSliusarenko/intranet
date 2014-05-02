<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskStatusTransition
 *
 * @ORM\Table(name="task_status_transitions")
 * @ORM\Entity
 */
class TaskStatusTransition 
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
     * @ORM\Column(name="fromid", type="integer")
     */
    private $fromid;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="toid", type="integer")
     */
    private $toid;


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
     * Set fromid
     *
     * @param integer $fromid
     * @return TaskStatusTransition
     */
    public function setFromid($fromid)
    {
        $this->fromid = $fromid;

        return $this;
    }

    /**
     * Get fromid
     *
     * @return integer 
     */
    public function getFromid()
    {
        return $this->fromid;
    }

    /**
     * Set toid
     *
     * @param integer $toid
     * @return TaskStatusTransition
     */
    public function setToid($toid)
    {
        $this->toid = $toid;

        return $this;
    }

    /**
     * Get toid
     *
     * @return integer 
     */
    public function getToid()
    {
        return $this->toid;
    }
    
    /**
     * @ORM\ManyToOne(targetEntity="TaskStatus", inversedBy="fromTransitions")
     * @ORM\JoinColumn(name="fromid")
     * @var TaskStatus
     */
    private $fromTaskStatus;
    
    /**
     * @ORM\ManyToOne(targetEntity="TaskStatus", inversedBy="toTransitions")
     * @ORM\JoinColumn(name="toid")
     * @var TaskStatus
     */
    private $toTaskStatus;

    /**
     * Set fromTaskStatus
     *
     * @param \Intranet\MainBundle\Entity\TaskStatus $fromTaskStatus
     * @return TaskStatusTransition
     */
    public function setFromTaskStatus(\Intranet\MainBundle\Entity\TaskStatus $fromTaskStatus = null)
    {
        $this->fromTaskStatus = $fromTaskStatus;

        return $this;
    }

    /**
     * Get fromTaskStatus
     *
     * @return \Intranet\MainBundle\Entity\TaskStatus 
     */
    public function getFromTaskStatus()
    {
        return $this->fromTaskStatus;
    }

    /**
     * Set toTaskStatus
     *
     * @param \Intranet\MainBundle\Entity\TaskStatus $toTaskStatus
     * @return TaskStatusTransition
     */
    public function setToTaskStatus(\Intranet\MainBundle\Entity\TaskStatus $toTaskStatus = null)
    {
        $this->toTaskStatus = $toTaskStatus;

        return $this;
    }

    /**
     * Get toTaskStatus
     *
     * @return \Intranet\MainBundle\Entity\TaskStatus 
     */
    public function getToTaskStatus()
    {
        return $this->toTaskStatus;
    }
}
