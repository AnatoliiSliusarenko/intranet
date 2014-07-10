<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskStatus
 *
 * @ORM\Table(name="task_statuses")
 * @ORM\Entity
 */
class TaskStatus 
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
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;
    
    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255)
     */
    private $color;
    
    /**
     * @var boolean
     * @ORM\Column(name="init_startdate", type="boolean")
     */
    private $initStartdate;
    
    /**
     * @var boolean
     * @ORM\Column(name="update_estimate", type="boolean")
     */
    private $updateEstimate;
    
    /**
     * @var boolean
     * @ORM\Column(name="update_enddate", type="boolean")
     */
    private $updateEnddate;
    
    /**
     * @var boolean
     * @ORM\Column(name="initial", type="boolean")
     */
    private $initial;
    
    /**
     * @var boolean
     * @ORM\Column(name="calc_time_start", type="boolean")
     */
    private $calcTimeStart;
    
    /**
     * @var boolean
     * @ORM\Column(name="calc_time_stop", type="boolean")
     */
    private $calcTimeStop;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="taskStatuses")
     * @ORM\JoinTable(name="task_status_role",
     *      joinColumns={@ORM\JoinColumn(name="statusid", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="roleid", referencedColumnName="id")}
     *      )
     * @var array
     */
    private $roles;
    
    /**
     * @ORM\OneToMany(targetEntity="TaskStatusTransition", mappedBy="fromTaskStatus")
     * @var array
     */
    private $fromTransitions;
    
    /**
     * @ORM\OneToMany(targetEntity="TaskStatusTransition", mappedBy="toTaskStatus")
     * @var array
     */
    private $toTransitions;
    
    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="status")
     * @var array
     */
    private $tasks;
    
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
     * Set label
     *
     * @param string $label
     * @return TaskStatus
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return TaskStatus
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set initStartdate
     *
     * @param boolean $initStartdate
     * @return TaskStatus
     */
    public function setInitStartdate($initStartdate)
    {
        $this->initStartdate = $initStartdate;

        return $this;
    }

    /**
     * Get initStartdate
     *
     * @return boolean 
     */
    public function getInitStartdate()
    {
        return $this->initStartdate;
    }

    /**
     * Set updateEstimate
     *
     * @param boolean $updateEstimate
     * @return TaskStatus
     */
    public function setUpdateEstimate($updateEstimate)
    {
        $this->updateEstimate = $updateEstimate;

        return $this;
    }

    /**
     * Get updateEstimate
     *
     * @return boolean 
     */
    public function getUpdateEstimate()
    {
        return $this->updateEstimate;
    }

    /**
     * Set updateEnddate
     *
     * @param boolean $updateEnddate
     * @return TaskStatus
     */
    public function setUpdateEnddate($updateEnddate)
    {
        $this->updateEnddate = $updateEnddate;

        return $this;
    }

    /**
     * Get updateEnddate
     *
     * @return boolean 
     */
    public function getUpdateEnddate()
    {
        return $this->updateEnddate;
    }

    /**
     * Set initial
     *
     * @param boolean $initial
     * @return TaskStatus
     */
    public function setInitial($initial)
    {
        $this->initial = $initial;

        return $this;
    }

    /**
     * Get initial
     *
     * @return boolean 
     */
    public function getInitial()
    {
        return $this->initial;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add roles
     *
     * @param \Intranet\MainBundle\Entity\Role $roles
     * @return TaskStatus
     */
    public function addRole(\Intranet\MainBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Intranet\MainBundle\Entity\Role $roles
     */
    public function removeRole(\Intranet\MainBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add fromTransitions
     *
     * @param \Intranet\MainBundle\Entity\TaskStatusTransition $fromTransitions
     * @return TaskStatus
     */
    public function addFromTransition(\Intranet\MainBundle\Entity\TaskStatusTransition $fromTransitions)
    {
        $this->fromTransitions[] = $fromTransitions;

        return $this;
    }

    /**
     * Remove fromTransitions
     *
     * @param \Intranet\MainBundle\Entity\TaskStatusTransition $fromTransitions
     */
    public function removeFromTransition(\Intranet\MainBundle\Entity\TaskStatusTransition $fromTransitions)
    {
        $this->fromTransitions->removeElement($fromTransitions);
    }

    /**
     * Get fromTransitions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFromTransitions()
    {
        return $this->fromTransitions;
    }

    /**
     * Add toTransitions
     *
     * @param \Intranet\MainBundle\Entity\TaskStatusTransition $toTransitions
     * @return TaskStatus
     */
    public function addToTransition(\Intranet\MainBundle\Entity\TaskStatusTransition $toTransitions)
    {
        $this->toTransitions[] = $toTransitions;

        return $this;
    }

    /**
     * Remove toTransitions
     *
     * @param \Intranet\MainBundle\Entity\TaskStatusTransition $toTransitions
     */
    public function removeToTransition(\Intranet\MainBundle\Entity\TaskStatusTransition $toTransitions)
    {
        $this->toTransitions->removeElement($toTransitions);
    }

    /**
     * Get toTransitions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getToTransitions()
    {
        return $this->toTransitions;
    }

    /**
     * Add tasks
     *
     * @param \Intranet\MainBundle\Entity\Task $tasks
     * @return TaskStatus
     */
    public function addTask(\Intranet\MainBundle\Entity\Task $tasks)
    {
        $this->tasks[] = $tasks;

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \Intranet\MainBundle\Entity\Task $tasks
     */
    public function removeTask(\Intranet\MainBundle\Entity\Task $tasks)
    {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }
    
    public function getInArray()
    {
    	return array(
    		'id' => $this->getId(),
    		'label' => $this->getLabel(),
    		'color' => $this->getColor(),
    		'initStartdate' => $this->getInitStartdate(),
    		'updateEstimate' => $this->getUpdateEstimate(),
    		'initial' => $this->getInitial()
    	);
    }
    
    public static function getInitialStatuses($em)
    {
    	return $em->getRepository('IntranetMainBundle:TaskStatus')
    			  ->findBy(array('initial' => true));
    }
    
    public static function getAllStatuses($em)
    {
    	$statuses =  $em->getRepository('IntranetMainBundle:TaskStatus')
    			  ->findAll();
    	return array_map(function($e){return $e->getInArray();}, $statuses);
    }
    
    public function isAllowedFor($user)
    {
    	foreach ($this->getRoles() as $role)
    	{
    		if ($user->hasRole($role)) return true;
    	}
    	return false;
    }

    /**
     * Set calcTimeStart
     *
     * @param boolean $calcTimeStart
     * @return TaskStatus
     */
    public function setCalcTimeStart($calcTimeStart)
    {
        $this->calcTimeStart = $calcTimeStart;

        return $this;
    }

    /**
     * Get calcTimeStart
     *
     * @return boolean 
     */
    public function getCalcTimeStart()
    {
        return $this->calcTimeStart;
    }

    /**
     * Set calcTimeStop
     *
     * @param boolean $calcTimeStop
     * @return TaskStatus
     */
    public function setCalcTimeStop($calcTimeStop)
    {
        $this->calcTimeStop = $calcTimeStop;

        return $this;
    }

    /**
     * Get calcTimeStop
     *
     * @return boolean 
     */
    public function getCalcTimeStop()
    {
        return $this->calcTimeStop;
    }
}
