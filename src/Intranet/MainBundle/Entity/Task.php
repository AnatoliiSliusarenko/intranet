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
}
