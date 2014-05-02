<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Role
 *
 * @ORM\Table(name="roles")
 * @ORM\Entity
 */
class Role implements RoleInterface
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
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     * @var array
     */
    private $users;
    
    /**
     * @ORM\ManyToMany(targetEntity="TaskStatus", mappedBy="roles")
     * @var array
     */
    private $taskStatuses;

    public function __construct()
    {
    	$this->users = new ArrayCollection();
    }
    
    public function getRole()
    {
    	return $this->getName();
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
     * @return Permission
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
     * @return Permission
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
    
    public static function getUserRole($em)
    {
    	return $em->getRepository("IntranetMainBundle:Role")->find(2);
    }
    
    public static function getAdminRole($em)
    {
    	return $em->getRepository("IntranetMainBundle:Role")->find(1);
    }

    /**
     * Add users
     *
     * @param \Intranet\MainBundle\Entity\User $users
     * @return Role
     */
    public function addUser(\Intranet\MainBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \Intranet\MainBundle\Entity\User $users
     */
    public function removeUser(\Intranet\MainBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
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
     * Add taskStatuses
     *
     * @param \Intranet\MainBundle\Entity\TaskStatus $taskStatuses
     * @return Role
     */
    public function addTaskStatus(\Intranet\MainBundle\Entity\TaskStatus $taskStatuses)
    {
        $this->taskStatuses[] = $taskStatuses;

        return $this;
    }

    /**
     * Remove taskStatuses
     *
     * @param \Intranet\MainBundle\Entity\TaskStatus $taskStatuses
     */
    public function removeTaskStatus(\Intranet\MainBundle\Entity\TaskStatus $taskStatuses)
    {
        $this->taskStatuses->removeElement($taskStatuses);
    }

    /**
     * Get taskStatuses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTaskStatuses()
    {
        return $this->taskStatuses;
    }
}
