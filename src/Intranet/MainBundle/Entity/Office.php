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
    
    /**
     * @ORM\OneToMany(targetEntity="Topic", mappedBy="office")
     * @var array
     */
    private $topics;
    
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
    
    public function getChildrenForUser($em, \Intranet\MainBundle\Entity\User $user)
    {
    	$officeChildren = $this->getChildren($em);
    	$userOfficesId = array_map(function($e){return $e->getId();}, $user->getOffices()->toArray());
    	$result = array();
    	
    	foreach ($officeChildren as $office)
    	{
    		if (in_array($office->getId(), $userOfficesId))
    			$result[] = $office;
    	}
    	
    	return $result;
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
    
    public function deleteAllChildren($em)
    {
    	$em->remove($this);
    	
    	$qb = $em->createQueryBuilder();
    	
    	$qb->select('t')
    	   ->from('IntranetMainBundle:Topic', 't')
    	   ->where('t.officeid = :officeid')
    	   ->setParameter('officeid', $this->getId());
    	   
    	$topics = $qb->getQuery()->getResult();
    	
    	foreach ($topics as $topic)
    	{
    		$topic->clearPosts($em);
    		$em->remove($topic);
    	}
    	
    	foreach ($this->getChildren($em) as $office)
    	{
    		$office->deleteAllChildren($em);
    	}
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
    public function addUser(\Intranet\MainBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }
    
    public function addUsers($em, $users)
    {
    	foreach ($users as $userid)
    	{
    		$user = $em->getRepository('IntranetMainBundle:User')->find($userid);
    		if ($user == null)
    			continue;
    		
    		$this->addUser($user);
    		$user->addOffice($this);
    		$em->persist($user);
    	}
    
    	return $this;
    }

    /**
     * Remove users
     *
     * @param \Intranet\MainBundle\Entity\User $users
     */
    public function removeUser(\Intranet\MainBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }
    
    public function resetUsers($em, $users)
    {
    	foreach ($this->users as $user)
    	{
    		$user->removeOffice($this);
    		$this->removeUser($user);
    		$em->persist($user);
    	}
    	
    	return $this->addUsers($em, $users);
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
    
    public function hasUser(\Intranet\MainBundle\Entity\User $user)
    {
    	return $this->users->contains($user);
    }

    /**
     * Add topic
     *
     * @param \Intranet\MainBundle\Entity\Topic $topic
     * @return Office
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
    			'userid' => $this->getUserid(),
    			'name' => $this->getName(),
    			'description' => $this->getDescription()
    	);
    }
}
