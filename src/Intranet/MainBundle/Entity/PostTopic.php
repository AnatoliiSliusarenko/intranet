<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PostTopic
 *
 * @ORM\Table(name="posts_topic")
 * @ORM\Entity
 */
class PostTopic
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
     * @ORM\Column(name="topicid", type="integer")
     */
    private $topicid;

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="postsTopic")
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
     * Set topicid
     *
     * @param integer $topicid
     * @return PostTopic
     */
    public function setTopicid($topicid)
    {
        $this->topicid = $topicid;

        return $this;
    }

    /**
     * Get topicid
     *
     * @return integer 
     */
    public function getTopicid()
    {
        return $this->topicid;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return PostTopic
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
    			'topicid' => $this->getTopicid(),
    			'userid' => $this->getUserid(),
    			'user' => $this->getUser()->getInArray(),
    			'message' => $this->getMessage(),
    			'posted' => $this->getPosted()
    		);
    }
    
    /**
     * Get post by topic
     * @return array
     */
    public static function getPostsByTopicId($r, $topic_id, $offset, $limit)
    {
    	$query = $r
		    	->createQueryBuilder('p')
		    	->select('p')
		    	->where('p.topicid = :topicid')
		    	->setParameter('topicid', $topic_id)
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
     * Add post by topic
     * @return PostTopic
     */
    public static function addPostByTopicId($em, $p)
    {
    	$user = $em->getRepository("IntranetMainBundle:User")->find(intval($p->userid));
    	$topic = $em->getRepository("IntranetMainBundle:Topic")->find(intval($p->entityid));
    	 
    	if (($topic == null) || ($user == null) || (trim($p->message) === ''))
    		return null;
    	
    	Notification::createNotification($em, $user, "message_topic", $user, $topic);
    	
    	$post = new PostTopic();
    	$post->setTopicid($topic->getId());
    	$post->setUserid($user->getId());
    	$post->setUser($user);
    	$post->setMessage($p->message);
    	$post->setPosted(new \DateTime($p->posted));
    	
    	$em->persist($post);
    	$em->flush();
    	
    	return $post->getInArray();
    }
    
    public static function getPostsCount($em, $topic_id)
    {
    	$query = $em->getRepository("IntranetMainBundle:PostTopic")
    				->createQueryBuilder('p')
    				->select('COUNT(p.id)') 
    				->where('p.topicid = :topicid')
    				->setParameter('topicid', $topic_id)
    				->getQuery();
    	
    	$total = $query->getSingleScalarResult();
    	
    	return $total;
    }
        
    public static function getNewPosts($em, $topic_id, $last_posted)
    {
    	$qb = $em->getRepository("IntranetMainBundle:PostTopic")
    				->createQueryBuilder('p');
    	$qb->select('p')
    	   ->where('p.topicid = :topicid')
    	   ->setParameter('topicid', $topic_id);
    	
    	
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
     * @return PostTopic
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
     * @return PostTopic
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
     * @return PostTopic
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
}
