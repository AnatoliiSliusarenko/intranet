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
	 * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
	 * @var array
	 */
	private $roles;
	
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
	 * @var string
	 *
	 * @ORM\Column(name="avatar", type="string", length=255)
	 */
	private $avatar;
	
	/**
	 * @ORM\OneToMany(targetEntity="Post", mappedBy="user")
	 */
	private $posts;
	
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
     * Add posts
     *
     * @param \Intranet\MainBundle\Entity\Post $posts
     * @return User
     */
    public function addPost(\Intranet\MainBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;

        return $this;
    }

    /**
     * Remove posts
     *
     * @param \Intranet\MainBundle\Entity\Post $posts
     */
    public function removePost(\Intranet\MainBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPosts()
    {
        return $this->posts;
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
    			'password' => $this->getPassword(),
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
    
    public static function forceLogin(UserInterface $user, $firewall_name, $securityContext, $request)
    {
    	$token = new UsernamePasswordToken($user, null, $firewall_name, $user->getRoles());
    	$securityContext->setToken($token);
    	$request->getSession()->set('_security_' . $firewall_name, serialize($token));
    }
    
    public static function getTopicMembers($em, $topic_id)
    {
    	$query = $em->getRepository("IntranetMainBundle:User")
    	->createQueryBuilder('u')
    	->select('u')
    	->innerJoin('u.posts', 'p', 'WITH', 'u.id = p.userid')
    	->where('p.topicid = :topicid')
    	->setParameter('topicid', $topic_id)
    	->getQuery();
    
    	$result = $query->getResult();
    
    	return array_map(function($user){
    		return $user->getInArray();
    	}, $result);
    }
    
    public function getAllUsers($em)
    {
    	$query = $em->getRepository("IntranetMainBundle:User")
			    	->createQueryBuilder('u')
			    	->select('u')
			    	->where('u.id != :userid')
			    	->setParameter('userid', $this->id)
			    	->orderBy('u.surname', 'ASC')
			    	->getQuery();
    
    	$result = $query->getResult();
    
    	return $result;
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
}
