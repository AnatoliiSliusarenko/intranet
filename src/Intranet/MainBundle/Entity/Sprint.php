<?php
/**
 * Created by PhpStorm.
 * User: Александр
 * Date: 2014-12-22
 * Time: 16:16
 */

namespace Intranet\MainBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Sprint
 *
 * @ORM\Table(name="sprints")
 * @ORM\Entity
 */
class Sprint {
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="office")
     * @var array
     */
    private $tasks;

    /**
     * @var integer
     *
     * @ORM\Column(name="statusid", type="integer")
     */
    private $statusid;

    /**
     * @ORM\ManyToOne(targetEntity="SprintStatus", inversedBy="sprint")
     * @ORM\JoinColumn(name="statusid")
     * @var SprintStatus
     */
    private $status;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->topics = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Sprint
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
     * Set description
     *
     * @param string $description
     * @return Sprint
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

    public function createSprint($em, $name, $description)
    {
        $sprint = new Sprint();
        $sprint->setName($name);
        $sprint->setDescription($description);
        $em->persist();
        $em->flush();
    }

    /**
     * Add tasks
     *
     * @param \Intranet\MainBundle\Entity\Task $tasks
     * @return Sprint
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
            'name' => $this->getName(),
            'description' => $this->getDescription()
        );
    }

    /**
     * Set statusid
     *
     * @param integer $statusid
     * @return Sprint
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
     * Set status
     *
     * @param \Intranet\MainBundle\Entity\SprintStatus $status
     * @return Sprint
     */
    public function setStatus(\Intranet\MainBundle\Entity\SprintStatus $status = null)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return \Intranet\MainBundle\Entity\SprintStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
