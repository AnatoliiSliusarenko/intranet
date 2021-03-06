<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Doctrine\ORM\Query\Expr\Join;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Intranet\MainBundle\Entity\UserRepository")
 */
class User implements UserInterface, \Serializable
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
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registered", type="datetime")
     */
    private $registered;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastactive", type="datetime")
     */
    private $lastactive;

    /**
     * @var boolean
     * @ORM\Column(name="active", type="boolean")
     */
	private $active;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="country", type="string", length=2)
	 */
	private $country;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
	 * @var array
	 */
	private $roles;
	
	/**
	 * @ORM\OneToMany(targetEntity="Task", mappedBy="user")
	 * @var array
	 */
	private $tasks;
	
	/**
	 * @ORM\OneToMany(targetEntity="Document", mappedBy="user")
	 * @var array
	 */
	private $documents;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Office", inversedBy="users")
	 * @var array
	 */
	private $offices;
	
	/**
	 * @ORM\OneToMany(targetEntity="Topic", mappedBy="user")
	 * @var array
	 */
	private $topics;
	
	/**
	 * @ORM\OneToMany(targetEntity="Notification", mappedBy="user")
	 * @ORM\OrderBy({"activated" = "DESC"})
	 * @var array
	 */
	private $notifications;
	
	/**
	 * @ORM\OneToMany(targetEntity="TaskActivityLog", mappedBy="user")
	 * @ORM\OrderBy({"loged" = "DESC"})
	 * @var array
	 */
	private $taskActivityLogs;
    
	/**
	 * @var string
	 *
	 * @ORM\Column(name="avatar", type="string", length=255)
	 */
	private $avatar;
	
	/**
	 * @ORM\OneToMany(targetEntity="PostTopic", mappedBy="user")
	 */
	private $postsTopic;
	
	/**
	 * @ORM\OneToMany(targetEntity="PostOffice", mappedBy="user")
	 */
	private $postsOffice;
	
	/**
	 * @ORM\OneToMany(targetEntity="PostTask", mappedBy="user")
	 */
	private $postsTask;
	
	/**
	 * @ORM\OneToOne(targetEntity="UserSettings", mappedBy="user")
	 */
	private $userSettings;

	/**
	 * @ORM\OneToOne(targetEntity="UserSettingsNotifications", mappedBy="user")
	 */
	private $userSettingsNotifications;
    /**
     * @var integer
     *
     * @ORM\Column(name="lastofficeid", type="integer")
     */
    private $lastOfficeId;

	public function __construct()
	{
		$this->active = true;
		$this->salt = md5(uniqid(null, true));
		$this->roles = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
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
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set registered
     *
     * @param \DateTime $registered
     * @return User
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;

        return $this;
    }

    /**
     * Get registered
     *
     * @return \DateTime 
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * Set lastactive
     *
     * @param \DateTime $lastactive
     * @return User
     */
    public function setLastactive($lastactive)
    {
        $this->lastactive = $lastactive;

        return $this;
    }

    /**
     * Get lastactive
     *
     * @return \DateTime 
     */
    public function getLastactive()
    {
        return $this->lastactive;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }
    
    /**
     * @inheritDoc 
     * @return multitype:string
     */
    public function getRoles()
    {
    	return $this->roles->toArray();
    }
    
    public function hasRole(\Intranet\MainBundle\Entity\Role $role)
    {
    	return $this->roles->contains($role);
    }
    
    /**
     * @inheritDoc
     */
    public function getSalt()
    {
    	return '7v8b6ghjb6834bdkjndsjb233409fjvsiu8892d';
    }
    
    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
    	return serialize(array(
    		$this->id,
    		$this->username,
    		$this->password,
    		$this->email,
    		$this->name,
    		$this->surname,
    		$this->registered,
    		$this->lastactive,
    		$this->active
    	));
    }
    
    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
    	list(
    		$this->id,
    		$this->username,
    		$this->password,
    		$this->email,
    		$this->name,
    		$this->surname,
    		$this->registered,
    		$this->lastactive,
    		$this->active) = unserialize($serialized);
    }
    
    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add role
     *
     * @param \Intranet\MainBundle\Entity\Role $role
     * @return User
     */
    public function addRole(\Intranet\MainBundle\Entity\Role $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Intranet\MainBundle\Entity\Role $roles
     */
    public function removeRole(\Intranet\MainBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Add postsTopic
     *
     * @param \Intranet\MainBundle\Entity\PostTopic $postsTopic
     * @return User
     */
    public function addPostTopic(\Intranet\MainBundle\Entity\PostTopic $postsTopic)
    {
        $this->postsTopic[] = $postsTopic;

        return $this;
    }

    /**
     * Remove postsTopic
     *
     * @param \Intranet\MainBundle\Entity\PostTopic $posts
     */
    public function removePostTopic(\Intranet\MainBundle\Entity\PostTopic $postTopic)
    {
        $this->postsTopic->removeElement($postTopic);
    }

    /**
     * Get postsTopic
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPostsTopic()
    {
        return $this->postsTopic;
    }
    
    /**
     * Add postsOffice
     *
     * @param \Intranet\MainBundle\Entity\PostOffice $postsOffice
     * @return User
     */
    public function addPostOffice(\Intranet\MainBundle\Entity\PostOffice $postsOffice)
    {
    	$this->postsOffice[] = $postsOffice;
    
    	return $this;
    }
    
    /**
     * Remove postsOffice
     *
     * @param \Intranet\MainBundle\Entity\PostOffice $postsOffice
     */
    public function removePostOffice(\Intranet\MainBundle\Entity\PostTopic $postOffice)
    {
    	$this->postsOffice->removeElement($postOffice);
    }
    
    /**
     * Get postsOffice
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostsOffice()
    {
    	return $this->postsOffice;
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
    			'username' => $this->getUsername(),
    			'email' => $this->getEmail(),
    			'name' => $this->getName(),
    			'surname' => $this->getSurname(),
    			'registered' => $this->getRegistered(),
    			'lastactive' => $this->getLastactive(),
    			'active' => $this->getActive(),
    			'avatar' => $this->getAvatar()
    	);
    }
    
    public static function isRegisteredByEmail($em, $email)
    {
    	$repository = $em->getRepository('IntranetMainBundle:User');
    	$query = $repository->createQueryBuilder('u')
						    ->where('u.email = :email')
						    ->setParameter('email', $email)
						    ->getQuery();

	    try {
	        return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
	        return null;
	    }
    }
    
    public static function isRegisteredByUsername($em, $username)
    {
    	$repository = $em->getRepository('IntranetMainBundle:User');
    	$query = $repository->createQueryBuilder('u')
    						->where('u.username = :username')
					    	->setParameter('username', $username)
					    	->getQuery();
    	
    	try {
    		return $query->getSingleResult();
    	} catch (\Doctrine\ORM\NoResultException $e) {
    		return null;
    	}
    }
    
    public static function getUserByUsername($em, $username)
    {
    	$repository = $em->getRepository('IntranetMainBundle:User');
    	$query = $repository->createQueryBuilder('u')
    	->where('u.username = :username')
    	->setParameter('username', $username)
    	->getQuery();
    	 
    	$result = $query->getResult();
    	return $result;
    }
    
    public static function forceLogin(UserInterface $user, $firewall_name, $securityContext, $request)
    {
    	$token = new UsernamePasswordToken($user, null, $firewall_name, $user->getRoles());
    	$securityContext->setToken($token);
    	$request->getSession()->set('_security_' . $firewall_name, serialize($token));
    }
    
    public static function addUser($em, $encoderFactory, $parameters)
    {
    	$user = new User();
    	$encoder = $encoderFactory->getEncoder($user);
    	$user->setName($parameters['name']);
    	$user->setSurname($parameters['surname']);
    	$user->setEmail($parameters['email']);
    	$user->setUsername($parameters['username']);
    	$user->setPassword($encoder->encodePassword($parameters['password'], $user->getSalt()));
    	$user->setRegistered(new \DateTime());
    	$user->setLastActive(new \DateTime());
    	$user->setCountry($parameters['country']);
    	$user->setAvatar('eleven.png');
    	$user->addRole(Role::getUserRole($em));
    	
    	if ($parameters['role'] == 'dev')
    		$user->addRole(Role::getDevRole($em));
    	else 
    		$user->addRole(Role::getClientRole($em));
    	
    	//add to public office
    	$tree = Office::getOfficeTree($em);
    	$publicOffice = $tree[0];
    	 
    	$user->addOffice($publicOffice);
    	$publicOffice->addUser($user);
    	 
    	$em->persist($publicOffice);
    	$em->persist($user);
    	$em->flush();
    	 
    	$settings = new UserSettings();
    	$settings->setUserid($user->getId());
    	$settings->setUser($user);
    	$settings->setShowHiddenTopics(true);
    	$settings->setDisableAllOnEmail(false);
    	$settings->setDisableAllOnSite(false);
    	 
    	$em->persist($settings);
    	$em->flush();
    	
    	return $user;
    }
    
    public static function getTopicMembers($em, $topic_id, $inArray=false)
    {
    	$query = $em->getRepository("IntranetMainBundle:User")
    				->createQueryBuilder('u')
    				->select('u')
    				->innerJoin('u.postsTopic', 'p', 'WITH', 'u.id = p.userid')
    				->where('p.topicid = :topicid')
    				->setParameter('topicid', $topic_id)
    				->getQuery();
    
    	$result = $query->getResult();
    
    	if ($inArray == true)
    	return array_map(function($user){
    		return $user->getInArray();
    	}, $result);
    	else return $result;
    }
    
    public static function getOfficeMembers($em, $office_id, $inArray = false)
    {
    	$query = $em->getRepository("IntranetMainBundle:User")
    	->createQueryBuilder('u')
    	->select('u')
    	->innerJoin('u.postsOffice', 'p', 'WITH', 'u.id = p.userid')
    	->where('p.officeid = :officeid')
    	->setParameter('officeid', $office_id)
    	->getQuery();
    
    	$result = $query->getResult();
    
    	if ($inArray == true)
    	return array_map(function($user){
    		return $user->getInArray();
    	}, $result);
    	else return $result;
    }
    
    public static function getTaskCommentsMembers($em, $task_id, $inArray = false)
    {
    	$query = $em->getRepository("IntranetMainBundle:User")
    	->createQueryBuilder('u')
    	->select('u')
    	->innerJoin('u.postsTask', 'p', 'WITH', 'u.id = p.userid')
    	->where('p.taskid = :taskid')
    	->setParameter('taskid', $task_id)
    	->getQuery();
    
    	$result = $query->getResult();
    
    	if ($inArray == true)
    		return array_map(function($user){
    		return $user->getInArray();
    	}, $result);
    	else return $result;
    }
    
    public function getAllUsers($em, $withOutMe = true, $inArray=false)
    {
    	$qb = $em->getRepository("IntranetMainBundle:User")
			     ->createQueryBuilder('u')
			     ->select('u')
			     ->orderBy('u.surname', 'ASC');
    	
    	if ($withOutMe == true)
    	{
    		$qb->where('u.id != :userid')
    		   ->setParameter('userid', $this->id);
    	}
    	
    	$users = $qb->getQuery()->getResult();
    
    	if ($inArray == true)
    		return array_map(function($user){
    			return $user->getInArray();
    		}, $users);
    	else return $users;
    }

    /**
     * Add offices
     *
     * @param \Intranet\MainBundle\Entity\Office $offices
     * @return User
     */
    public function addOffice(\Intranet\MainBundle\Entity\Office $office)
    {
        $this->offices[] = $office;

        return $this;
    }

    /**
     * Remove offices
     *
     * @param \Intranet\MainBundle\Entity\Office $offices
     */
    public function removeOffice(\Intranet\MainBundle\Entity\Office $office)
    {
        $this->offices->removeElement($office);
    }

    /**
     * Get offices
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOffices()
    {
        return $this->offices;
    }
    
    public function getOfficesWithoutOne(\Intranet\MainBundle\Entity\Office $office)
    {
    	$offices = $this->offices;
    	$offices->removeElement($office);
    	return $offices;
    }

    /**
     * Add topic
     *
     * @param \Intranet\MainBundle\Entity\Topic $topic
     * @return User
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
     * Add notifications
     *
     * @param \Intranet\MainBundle\Entity\Notification $notifications
     * @return User
     */
    public function addNotification(\Intranet\MainBundle\Entity\Notification $notifications)
    {
        $this->notifications[] = $notifications;

        return $this;
    }

    /**
     * Remove notifications
     *
     * @param \Intranet\MainBundle\Entity\Notification $notifications
     */
    public function removeNotification(\Intranet\MainBundle\Entity\Notification $notifications)
    {
        $this->notifications->removeElement($notifications);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }
    
    public function clearNotifications($em)
    {
    	$qb = $em->createQueryBuilder();
    	 
    	$qb->delete('IntranetMainBundle:Notification', 'n')
    	   ->where('n.userid = :userid')
    	   ->setParameter('userid', $this->getId());
    	 
    	return $qb->getQuery()->getResult();
    }

    /**
     * Add postsTopic
     *
     * @param \Intranet\MainBundle\Entity\PostTopic $postsTopic
     * @return User
     */
    public function addPostsTopic(\Intranet\MainBundle\Entity\PostTopic $postsTopic)
    {
        $this->postsTopic[] = $postsTopic;

        return $this;
    }

    /**
     * Remove postsTopic
     *
     * @param \Intranet\MainBundle\Entity\PostTopic $postsTopic
     */
    public function removePostsTopic(\Intranet\MainBundle\Entity\PostTopic $postsTopic)
    {
        $this->postsTopic->removeElement($postsTopic);
    }

    /**
     * Add postsOffice
     *
     * @param \Intranet\MainBundle\Entity\PostOffice $postsOffice
     * @return User
     */
    public function addPostsOffice(\Intranet\MainBundle\Entity\PostOffice $postsOffice)
    {
        $this->postsOffice[] = $postsOffice;

        return $this;
    }

    /**
     * Remove postsOffice
     *
     * @param \Intranet\MainBundle\Entity\PostOffice $postsOffice
     */
    public function removePostsOffice(\Intranet\MainBundle\Entity\PostOffice $postsOffice)
    {
        $this->postsOffice->removeElement($postsOffice);
    }

    /**
     * Add tasks
     *
     * @param \Intranet\MainBundle\Entity\Task $tasks
     * @return User
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

    /**
     * Add taskActivityLog
     *
     * @param \Intranet\MainBundle\Entity\TaskActivityLog $taskActivityLog
     * @return User
     */
    public function addTaskActivityLog(\Intranet\MainBundle\Entity\TaskActivityLog $taskActivityLog)
    {
        $this->taskActivityLogs[] = $taskActivityLog;

        return $this;
    }

    /**
     * Remove $taskActivityLog
     *
     * @param \Intranet\MainBundle\Entity\TaskActivityLog $taskActivityLog
     */
    public function removeTaskActivityLog(\Intranet\MainBundle\Entity\TaskActivityLog $taskActivityLog)
    {
        $this->taskActivityLogs->removeElement($taskActivityLog);
    }

    /**
     * Get taskActivityLogs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTaskActivityLogs()
    {
        return $this->taskActivityLogs;
    }

    /**
     * Add postsTask
     *
     * @param \Intranet\MainBundle\Entity\PostTask $postsTask
     * @return User
     */
    public function addPostsTask(\Intranet\MainBundle\Entity\PostTask $postsTask)
    {
        $this->postsTask[] = $postsTask;

        return $this;
    }

    /**
     * Remove postsTask
     *
     * @param \Intranet\MainBundle\Entity\PostTask $postsTask
     */
    public function removePostsTask(\Intranet\MainBundle\Entity\PostTask $postsTask)
    {
        $this->postsTask->removeElement($postsTask);
    }

    /**
     * Get postsTask
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPostsTask()
    {
        return $this->postsTask;
    }

    /**
     * Add documents
     *
     * @param \Intranet\MainBundle\Entity\Document $documents
     * @return User
     */
    public function addDocument(\Intranet\MainBundle\Entity\Document $documents)
    {
        $this->documents[] = $documents;

        return $this;
    }

    /**
     * Remove documents
     *
     * @param \Intranet\MainBundle\Entity\Document $documents
     */
    public function removeDocument(\Intranet\MainBundle\Entity\Document $documents)
    {
        $this->documents->removeElement($documents);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Set userSettings
     *
     * @param \Intranet\MainBundle\Entity\UserSettings $userSettings
     * @return User
     */
    public function setUserSettings(\Intranet\MainBundle\Entity\UserSettings $userSettings = null)
    {
        $this->userSettings = $userSettings;

        return $this;
    }

    /**
     * Get userSettings
     *
     * @return \Intranet\MainBundle\Entity\UserSettings 
     */
    public function getUserSettings()
    {
        return $this->userSettings;
    }
	
    /**
     * Set userSettingsNotifications
     *
     * @param \Intranet\MainBundle\Entity\UserSettingsNotifications $userSettingsNotifications
     * @return User
     */
    public function setUserSettingsNotifications(\Intranet\MainBundle\Entity\UserSettingsNotifications $userSettingsNotifications = null)
    {
    	$this->userSettingsNotifications = $userSettingsNotifications;
    
    	return $this;
    }
    
    /**
     * Get userSettingsNotifications
     *
     * @return \Intranet\MainBundle\Entity\UserSettingsNotifications
     */
    public function getUserSettingsNotifications()
    {
    	return $this->userSettingsNotifications;
    }
    
    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
    }
    
    public function isInProgress($em)
    {
    	$calculatingStatuses = $em->getRepository('IntranetMainBundle:TaskStatus')->findByCalcTimeStart(true);
    	$calculatingStatusesIds = array_map(function($status){return $status->getId();}, $calculatingStatuses);
    	
    	$qb = $em->createQueryBuilder();
    	
    	$repository = $em->getRepository('IntranetMainBundle:Task');
    	$query = $repository->createQueryBuilder('t')
    	->where('t.userid = :userid')
    	->andWhere($qb->expr()->in('t.status', $calculatingStatusesIds))
    	->setParameter('userid', $this->getId())
    	->getQuery();
    	
    	$result = $query->getResult();
    	
    	return count($result);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getLastOfficeId()
    {
        return $this->lastOfficeId;
    }

    /**
     * Set lastofficeid
     *
     * @param integer $lastOfficeid
     * @return User
     */
    public function setLastOfficeId( $lastOfficeid)
    {
        $this->lastOfficeId = $lastOfficeid;
        return $this;
    }

    public static function SetOfficeForUser($em, $userid,$offceid)
    {
        $office = $em->getRepository('IntranetMainBundle:Office')->find($offceid);
        if($office->getOfficeid() == 1)
        {
            $user = $em->getRepository('IntranetMainBundle:User')->find($userid);
            $user->setLastOfficeId($offceid);
            $em->persist($user);
            $em->flush();
        }
    }
}
