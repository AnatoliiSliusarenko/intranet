<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Office
 *
 * @ORM\Table(name="offices")
 * @ORM\Entity
 */
class Office 
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
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer")
     */
    private $userid;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="offices")
     * @var array
     */
    private $users;
    
    public $children = null;
    
    private static function fetchChildren($em, $officeid)
    {
    	$children = $em->getRepository('IntranetMainBundle:Office')
    	->findBy(array('officeid' => $officeid));
    	 
    	return $children;
    }
    
    /**
     * Get children
     *
     * @return array
     */
    public function getChildren($em)
    {
    	if ($this->children == null)
    		$this->children = $this->fetchChildren($em, $this->id);
    	 
    	return $this->children;
    }
    
    /**
     * Get Office tree
     *
     * @return array
     */
    public static function getOfficeTree($em, &$office = null)
    {
    	$tree = ($office == null) ? self::fetchChildren($em, 0) : $office->getChildren($em);
    
    	foreach ($tree as $office)
    	{
    		if ($office->getChildren($em) != array())
    			$office->getOfficeTree($em, $office);
    	}
    	 
    	return $tree;
    }
    
    /**
     * Get breadcrumbs
     *
     * @return array
     */
    public function getBreadcrumbs($em)
    {
    	$breadcrumbs = array();
    	$office = $this;
    	 
    	while ($office->getOfficeid() != 0)
    	{
    		$parent = $em->getRepository('IntranetMainBundle:Office')
    		->find($office->getOfficeid());
    
    		array_unshift($breadcrumbs, $parent);
    
    		$office = $parent;
    	}
    	 
    	return $breadcrumbs;
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
     * @return Office
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
     * Set userid
     *
     * @param integer $userid
     * @return Office
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
     * Set name
     *
     * @param string $name
     * @return Office
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
     * @return Office
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
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = array();
    }

    /**
     * Add users
     *
     * @param \Intranet\MainBundle\Entity\User $users
     * @return Office
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
}
