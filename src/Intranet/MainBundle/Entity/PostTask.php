<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PostTask
 *
 * @ORM\Table(name="posts_task")
 * @ORM\Entity
 */
class PostTask
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
     * @ORM\Column(name="taskid", type="integer")
     */
    private $taskid;

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
     * @var \DateTime
     *
     * @ORM\Column(name="edited", type="datetime")
     */
    private $edited;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="postsTopic")
     * @ORM\JoinColumn(name="userid", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="posts")
     * @ORM\JoinColumn(name="taskid")
     * @var Task
     */
    private $task;
    
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
     * Set taskid
     *
     * @param integer $taskid
     * @return PostTask
     */
    public function setTaskid($taskid)
    {
        $this->taskid = $taskid;

        return $this;
    }

    /**
     * Get taskid
     *
     * @return integer 
     */
    public function getTaskid()
    {
        return $this->taskid;
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
    			'taskid' => $this->getTaskid(),
    			'userid' => $this->getUserid(),
    			'user' => $this->getUser()->getInArray(),
    			'message' => $this->getMessage(),
    			'posted' => $this->getPosted(),
    			'edited' => $this->getEdited()
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
     * Add post by task
     * @return PostTask
     */
    public static function addPostByTaskId($em, $notifier, $p)
    {
    	$user = $em->getRepository("IntranetMainBundle:User")->find(intval($p->userid));
    	$task = $em->getRepository("IntranetMainBundle:Task")->find(intval($p->entityid));
    	 
    	if (($task == null) || ($user == null) || (trim($p->message) === ''))
    		return null;
    	
    	$notifier->createNotification("task_comment", $task, $task->getOffice());
    	
    	$post = new PostTask();
    	$post->setTaskid($task->getId());
    	$post->setTask($task);
    	$post->setUserid($user->getId());
    	$post->setUser($user);
    	$post->setMessage($p->message);
    	$post->setPosted(new \DateTime());
    	
    	$em->persist($post);
    	$em->flush();
    	
    	return $post->getInArray();
    }
    
    public static function editPostByTaskId($em, $p)
    {
    	$post = $em->getRepository("IntranetMainBundle:PostTask")->find(intval($p->postid));
    	 
    	if (($post == null) || (trim($p->message) === ''))
    		return null;
    	 
    	$post->setMessage($p->message);
    	$post->setEdited(new \DateTime());
    
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
    		$qb->andWhere('p.posted > :last_posted OR p.edited > :last_posted')
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
    
    /**
     * Set edited
     *
     * @param \DateTime $edited
     * @return PostTask
     */
    public function setEdited($edited)
    {
    	$this->edited = $edited;
    
    	return $this;
    }
    
    /**
     * Get edited
     *
     * @return \DateTime
     */
    public function getEdited()
    {
    	return $this->edited;
    }

    /**
     * Set task
     *
     * @param \Intranet\MainBundle\Entity\Task $task
     * @return PostTask
     */
    public function setTask(\Intranet\MainBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \Intranet\MainBundle\Entity\Task 
     */
    public function getTask()
    {
        return $this->task;
    }
}
