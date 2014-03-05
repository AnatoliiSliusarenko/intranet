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
}
