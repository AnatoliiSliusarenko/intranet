<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Topic
 *
 * @ORM\Table(name="topics")
 * @ORM\Entity
 */
class Topic
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
     * @var integer
     *
     * @ORM\Column(name="userid", type="integer")
     */
    private $userid;

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
     * @ORM\ManyToOne(targetEntity="Office", inversedBy="topics")
     * @ORM\JoinColumn(name="officeid")
     * @var Office
     */
    private $office;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="topics")
     * @ORM\JoinColumn(name="userid")
     * @var User
     */
    private $user;
    
    public $children = null;
    
    private static function fetchChildren($em, $parentid)
    {
    	$children = $em->getRepository('IntranetMainBundle:Topic')
    	->findBy(array('parentid' => $parentid));
    	
    	return $children;
    }
    
    public function __construct()
    {
    	$this->children = array();
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
     * Set parentid
     *
     * @param integer $parentid
     * @return Topic
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
    
    public function getParent($em)
    {
    	return $em->getRepository('IntranetMainBundle:Topic')->find($this->parentid);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Topic
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
     * @return Topic
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
    	$topicChildren = $this->getChildren($em);
    	$result = array();
    	 
    	foreach ($topicChildren as $topic)
    	{
    		if ($topic->getOffice()->hasUser($user))
    			$result[] = $topic;
    	}
    	 
    	return $result;
    }
    
    public function getChildrenForOffice($em, \Intranet\MainBundle\Entity\Office $office = null)
    {
    	if ($office == null) $office = $this->getOffice();
    	
    	$topicChildren = $this->getChildren($em);
    	$result = array();
    
    	foreach ($topicChildren as $topic)
    	{
    		if ($topic->getOfficeid() == $office->getId())
    			$result[] = $topic;
    	}
    
    	return $result;
    }
    
    /**
     * Get Topic tree
     *
     * @return array
     */
    public static function getTopicTree($em, &$topic = null)
    {
    	$tree = ($topic == null) ? self::fetchChildren($em, 0) : $topic->getChildren($em);
    	    	
    	foreach ($tree as $topic)
    	{
    		if ($topic->getChildren($em) != array())
    			$topic->getTopicTree($em, $topic);
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
    	$topic = $this;
    	
    	while ($topic->getParentid() != 0)
    	{
    		$parent = $em->getRepository('IntranetMainBundle:Topic')
    					 ->find($topic->getParentid());
    		
    		array_unshift($breadcrumbs, $parent);
    		
    		$topic = $parent;
    	}
    	
    	return $breadcrumbs;
    }

    /**
     * Set office
     *
     * @param \Intranet\MainBundle\Entity\Office $office
     * @return Topic
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
     * Set officeid
     *
     * @param integer $officeid
     * @return Topic
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
    
    public function clearPosts($em)
    {
    	$qb = $em->createQueryBuilder();
    	
    	$qb->delete('IntranetMainBundle:PostTopic', 'p')
    	   ->where('p.topicid = :topicid')
    	   ->setParameter('topicid', $this->getId());
    	
    	$result = $qb->getQuery()->getResult();
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return Topic
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
     * @return Topic
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
    
    public function deleteAllChildren($em)
    {
    	$em->remove($this);
    	$this->clearPosts($em);
    	
    	foreach ($this->getChildren($em) as $topic)
    	{
    		$topic->deleteAllChildren($em);
    	}
    }
}
