<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Document
 *
 * @ORM\Table(name="documents")
 * @ORM\Entity
 */
class Document 
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank;
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="uploaded", type="datetime")
     */
    private $uploaded;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="documents")
     * @ORM\JoinColumn(name="userid")
     * @var User
     */
    private $user;
    
    function __construct($userid)
    {
    	$this->setUserid($userid);
    	$this->setUploaded(new \DateTime());
    }
    
    public function getAbsolutePath()
    {
    	return null === $this->path
    		   ? null
    		   : $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getWebPath()
    {
    	return null === $this->path
    			? null
    			: $this->getUploadDir().'/'.$this->path;
    }
    
    protected function getUploadRootDir()
    {
    	return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    
    protected function getUploadDir()
    {
    	return 'documents';
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
     * Set name
     *
     * @param string $name
     * @return Document
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
     * Set path
     *
     * @param string $path
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Sets file
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
    	$this->file = $file;
    	
    	$this->path = $this->getFile()->getClientOriginalName();
    	
    	$this->name = $this->getFile()->getClientOriginalName();
    }
    
    /**
     * Get file.
     * 
     * @return UploadedFile
     */
    public function getFile()
    {
    	return $this->file;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return Document
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
    
    public function upload()
    {
    	if (null === $this->getFile()) return;
    	
    	$this->getFile()->move($this->getUploadRootDir(), $this->getFile()->getClientOriginalName());
    	
    	$this->file = null;
    }

    /**
     * Set uploaded
     *
     * @param \DateTime $uploaded
     * @return Document
     */
    public function setUploaded($uploaded)
    {
        $this->uploaded = $uploaded;

        return $this;
    }

    /**
     * Get uploaded
     *
     * @return \DateTime 
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }

    /**
     * Set user
     *
     * @param \Intranet\MainBundle\Entity\User $user
     * @return Document
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
}
