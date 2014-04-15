<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PostOffice
 *
 * @ORM\Table(name="posts_office")
 * @ORM\Entity
 */
class PostOffice
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
     * @ORM\Column(name="message", type="text")
     */
    private $message;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="posted", type="datetime")
     */
    private $posted;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="edited", type="boolean")
     */
    private $edited;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="postsOffice")
     * @ORM\JoinColumn(name="userid", referencedColumnName="id")
     */
    private $user;
    
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
     * @return PostOffice
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
     * Set message
     *
     * @param string $message
     * @return PostOffice
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
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
    			'user' => $this->getUser()->getInArray(),
    			'message' => $this->getMessage(),
    			'posted' => $this->getPosted(),
    			'edited' => $this->getEdited()
    		);
    }
    
    /**
     * Get post by office
     * @return array
     */
    public static function getPostsByOfficeId($r, $office_id, $offset, $limit)
    {
    	$query = $r
		    	->createQueryBuilder('p')
		    	->select('p')
		    	->where('p.officeid = :officeid')
		    	->setParameter('officeid', $office_id)
		    	->setFirstResult( $offset )
		    	->setMaxResults( $limit )
		    	->orderBy('p.posted', 'DESC')
		    	->getQuery();
    	
    	$posts = $query->getResult();
    	
    	return array_map(function($post){
    		return $post->getInArray();
    	}, $posts);
    }

    /**
     * Add post by office
     * @return PostOffice
     */
    public static function addPostByOfficeId($em, $p)
    {
    	$user = $em->getRepository("IntranetMainBundle:User")->find(intval($p->userid));
    	$office = $em->getRepository("IntranetMainBundle:Office")->find(intval($p->entityid));
    	 
    	if (($office == null) || ($user == null) || (trim($p->message) === ''))
    		return null;
    	
    	Notification::createNotification($em, $user, "message_office", $user, $office);
    	
    	$post = new PostOffice();
    	$post->setOfficeid($office->getId());
    	$post->setUserid($user->getId());
    	$post->setUser($user);
    	$post->setMessage($p->message);
    	$post->setPosted(new \DateTime($p->posted));
    	
    	$em->persist($post);
    	$em->flush();
    	
    	return $post->getInArray();
    }
    
    public static function editPostByOfficeId($em, $p)
    {
    	$post = $em->getRepository("IntranetMainBundle:PostOffice")->find(intval($p->postid));
    	
    	if (($post == null) || (trim($p->message) === ''))
    		return null;
    	
    	$post->setMessage($p->message);
    	$post->setEdited(true);
    	 
    	$em->persist($post);
    	$em->flush();
    	 
    	return $post->getInArray();
    }
    
    public static function getPostsCount($em, $office_id)
    {
    	$query = $em->getRepository("IntranetMainBundle:PostOffice")
    				->createQueryBuilder('p')
    				->select('COUNT(p.id)') 
    				->where('p.officeid = :officeid')
    				->setParameter('officeid', $office_id)
    				->getQuery();
    	
    	$total = $query->getSingleScalarResult();
    	
    	return $total;
    }
        
    public static function getNewPosts($em, $office_id, $last_posted)
    {
    	$qb = $em->getRepository("IntranetMainBundle:PostOffice")
    				->createQueryBuilder('p');
    	$qb->select('p')
    	   ->where('p.officeid = :officeid')
    	   ->setParameter('officeid', $office_id);
    	
    	
    	if ($last_posted != null)
    	{
    		$qb->andWhere('p.posted > :last_posted')
    		   ->setParameter('last_posted', $last_posted);
    	}
        		
    	$result = $qb->getQuery()->getResult();
    
    	return array_map(function($post){
    		return $post->getInArray();
    	}, $result);
    }
    
    /**
     * Set userid
     *
     * @param integer $userid
     * @return PostOffice
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
     * @return PostOffice
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
     * Set posted
     *
     * @param \DateTime $posted
     * @return PostOffice
     */
    public function setPosted($posted)
    {
        $this->posted = $posted;

        return $this;
    }

    /**
     * Get posted
     *
     * @return \DateTime 
     */
    public function getPosted()
    {
        return $this->posted;
    }

    /**
     * Set edited
     *
     * @param boolean $edited
     * @return PostOffice
     */
    public function setEdited($edited)
    {
        $this->edited = $edited;

        return $this;
    }

    /**
     * Get edited
     *
     * @return boolean 
     */
    public function getEdited()
    {
        return $this->edited;
    }
}
