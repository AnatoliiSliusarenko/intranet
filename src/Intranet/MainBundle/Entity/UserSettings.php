<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Document
 *
 * @ORM\Table(name="user_settings")
 * @ORM\Entity
 */
class UserSettings 
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
     * @ORM\Column(name="userid", type="integer")
     */
    private $userid;
    
    /**
     * @var boolean
     * @ORM\Column(name="show_hidden_topics", type="boolean")
     */
    private $showHiddenTopics;
    
    /**
     * @var boolean
     * @ORM\Column(name="disable_message_on_email", type="boolean")
     */
    private $disableAllOnEmail;
    
    /**
     * @var boolean
     * @ORM\Column(name="disable_message_on_site", type="boolean")
     */
    private $disableAllOnSite;
    
    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="userSettings")
     * @ORM\JoinColumn(name="userid", referencedColumnName="id")
     * @var User
     */
    protected $user;
    

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
     * Set userid
     *
     * @param integer $userid
     * @return UserSettings
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
     * Set showHiddenTopics
     *
     * @param boolean $showHiddenTopics
     * @return UserSettings
     */
    public function setShowHiddenTopics($showHiddenTopics)
    {
        $this->showHiddenTopics = $showHiddenTopics;

        return $this;
    }

    /**
     * Get showHiddenTopics
     *
     * @return boolean 
     */
    public function getShowHiddenTopics()
    {
        return $this->showHiddenTopics;
    }
	
    /**
     * Set disableAllOnEmail
     *
     * @param boolean $disableAllOnEmail
     * @return UserSettings
     */
    public function setDisableAllOnEmail($disableAllOnEmail)
    {
    	$this->disableAllOnEmail = $disableAllOnEmail;
    
    	return $this;
    }
    
    /**
     * Get disableAllOnEmail
     *
     * @return boolean
     */
    public function getDisableAllOnEmail()
    {
    	return $this->disableAllOnEmail;
    }
    
    /**
     * Set disableAllOnSite
     *
     * @param boolean $disableAllOnSite
     * @return UserSettings
     */
    public function setDisableAllOnSite($disableAllOnSite)
    {
    	$this->disableAllOnSite = $disableAllOnSite;
    
    	return $this;
    }
    
    /**
     * Get disableAllOnSite
     *
     * @return boolean
     */
    public function getDisableAllOnSite()
    {
    	return $this->disableAllOnSite;
    }
    
    /**
     * Set user
     *
     * @param \Intranet\MainBundle\Entity\User $user
     * @return UserSettings
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
     * Get inArray
     *
     * @return array
     */
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'userid' => $this->getUserid(),
    			'show_hidden_topics' => $this->getShowHiddenTopics(),
    			'disable_message_on_site' => $this->getDisableAllOnSite(),
    			'disable_message_on_email' => $this->getDisableAllOnEmail()
    	);
    }
    
}
