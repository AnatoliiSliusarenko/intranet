<?php
namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Personal
 *
 * @ORM\Table(name="personal_pages")
 * @ORM\Entity
 */
class PersonalPage
{
	/**
	 * @var integer
	 * 
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/** @var integer
	*
	* @ORM\Column(name="user_id", type="integer")
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $userid;
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="office_id", type="integer")
	 */
	private $officeid;
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="topic_id", type="integer")
	 */
	private $topicid;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="name_window", type="string", length=255, unique=true)
	 */
	private $namewindow;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="window_id", type="integer")
	 */

	private $windowid;
	
	/**
	 *@var integer
	 *
	 * @ORM\Column(name="dropdown", type="integer")
	 */
	private $dropdown;
	
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
     * Set officeid
     *
     * @param integer $officeid
     * @return Personal
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
     * Set topicid
     *
     * @param integer $topicid
     * @return Personal
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
     * Set namewindow
     *
     * @param string $namewindow
     * @return Personal
     */
    public function setNamewindow($namewindow)
    {
        $this->namewindow = $namewindow;

        return $this;
    }

    /**
     * Get namewindow
     *
     * @return string 
     */
    public function getNamewindow()
    {
        return $this->namewindow;
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
     * Set userid
     *
     * @param integer $userid
     * @return Personal
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }
    public function getInArray()
    {
    	return array(
    			'id' => $this->getId(),
    			'userid' => $this->getUserid(),
    			'officeid' => $this->getOfficeid(),
    			'topicid' => $this->getTopicid(),
    			'edited' => $this->getEdited()
    	);
    }

    /**
     * Set windowid
     *
     * @param integer $windowid
     * @return PersonalPage
     */
    public function setWindowid($windowid)
    {
        $this->windowid = $windowid;

        return $this;
    }

    /**
     * Get windowid
     *
     * @return integer 
     */
    public function getWindowid()
    {
        return $this->windowid;
    }
    
    /**
     * Set dropdown
     * @param integer $dropdown
     * @return PersonalPage
     */
    
    public static function getDataForUser($em , $userid)
    {
    	$office_id_arr = array();
    	$topic_id_arr = array();
    	$windows_id_arr = array();
    	$topics = array();
    	$offices = array();
    	$windows = array();
    	$personal_data = $em->getRepository('IntranetMainBundle:PersonalPage')->findAll($userid);
    	if ($personal_data == null)
			return;
    	foreach ($personal_data as $personal_record)
    	{
    		array_push($windows_id_arr, $personal_record->getWindowid());
    		array_push($office_id_arr,$personal_record->getOfficeId());
    		if($personal_record->getTopicId()!= null)
    			array_push($topic_id_arr,$personal_record->getTopicId());
    			
    	}
    	$office_id_arr = array_unique($office_id_arr);
    	$office_id_arr = array_unique($office_id_arr);
    	$windows_id_arr = array_unique($windows_id_arr);
    	foreach ($topic_id_arr as $topic_id)
    		array_push($topics, $topic = $em->getRepository('IntranetMainBundle:Topic')->find($topic_id));
    	foreach ($office_id_arr as $id)
    		array_push($offices, $office = $em->getRepository('IntranetMainBundle:Office')->find($id));
    	
    	foreach ($windows_id_arr as $window_id)
    	{
    		$allWindows = $em->getRepository('IntranetMainBundle:PersonalPage')->findByWindowid($window_id);
    		foreach ($allWindows as $window)
    			if($window->getUserid()==$userid)
    				array_push($windows, $window);
    	}
    	for($i=0;$i<count($windows);$i++)
    	{
    		$tmp=$windows[$i];
    		$count = 1;
    		for($j=$i+1;$j<count($windows);$j++)
    		{
    			if($windows[$i]->getNamewindow()==$windows[$j]->getNamewindow())
    			{
    				$windows[$j]->setNamewindow($windows[$j]->getNamewindow()."	($count)");
    				$count++;
    			}
    		}    		
    	}
    	$parameters = array (
    			"topics" => $topics,
    			"em" => $em,
    			"offices" => $offices,
    			"windows" => array()
    	);
    	if(count($windows) != 0)
    	{
    		$tmp = array_shift($windows);
    		array_push($parameters["windows"], $tmp);
    	}
    	foreach ($windows as $wind)
    	{
    		$flag = false;
    		foreach ($parameters["windows"] as $w)
    			if($w->getWindowid() == $wind->getWindowid())
    				$flag = true;
    		if(!$flag) 
    			array_push($parameters["windows"], $wind);
    	}
    	return $parameters;
    }
    
    public static function getTopicsForWindow($officeid, $em, $topics, $userId)
    {
    	$topicsPersonal = $em->getRepository('IntranetMainBundle:PersonalPage')->findByWindowid($officeid);
    	$window_topics = array();
    	$mas = array();
    	foreach ($topicsPersonal as $topic) 
    		if($topic->getTopicid() != null)
    		{
    			array_push($window_topics, $topic);
    			array_push($mas, $topic->getId());
    		}
    	return array("topics" => $window_topics, "tabId" => $mas);
    }
    
    public static function getTopicName($em ,$topicId)
    {
    	$topic = $em->getRepository('IntranetMainBundle:Topic')->find($topicId);
    	return $topic->getName();
    }
    
    public static function getOfficeForWindow($em, $window, $userId)
    {
    	$offices = $em->getRepository('IntranetMainBundle:PersonalPage')->findByTopicid(NULL);
    	foreach ($offices as $office)
    		if($office->getWindowid()==$window->getWindowid()
    	&& $office->getUserid() == $userId)
    			return $office;
    	return null;
    }
    
    public static function getAllIdForUser($em, $userid)
    {
    	$office_id_arr = array();
    	$topic_id_arr = array();
    	$windows = array();
    	$windows_id_arr = array();
    	$personal_data = $em->getRepository('IntranetMainBundle:PersonalPage')->findByUserid($userid);
    	if ($personal_data == null)
    		return ;
    	foreach ($personal_data as $personal_record)
    	{
    		array_push($windows_id_arr, $personal_record->getWindowid());
    		if(array_search($personal_record->getTopicId(), $office_id_arr) == false)
    		if($personal_record->getTopicId()!= null)
    			array_push($topic_id_arr,$personal_record->getTopicId());
    		 
    	}
    	$windows_id_arr = array_unique($windows_id_arr);
    	foreach ($windows_id_arr as $window_id)
    	{
    		$windowAll = $em->getRepository('IntranetMainBundle:PersonalPage')->findByWindowid($window_id);
    		foreach($windowAll as $value)
    			array_push($windows, $value);
    	}
    		
    	foreach ($windows as $window)
    		if($window->getTopicid() == NULL 
    				&& $window->getUserid() == $userid)
    			array_push($office_id_arr, $window->getOfficeid());
    	$result_array = array(
    		"officesid" => $office_id_arr,
    		"topicsid" => $topic_id_arr
    	);
    	return $result_array;
    }
    
    public static function getWindowsName($em, $userId, $officeName)
    {
    	$arrayWindows = array();
    	$result = array();
    	$res = array();
    	$records = $em->getRepository('IntranetMainBundle:PersonalPage')->findByUserid($userId);
    	if(count($records) != 0)
    	{
    		$tmp = array_shift($records);
    		array_push($arrayWindows, $tmp);
    	}
    	foreach ($records as $record)
    	{
    		$flag = false;
    		foreach ($arrayWindows as $value)
    			if ($value->getWindowid() == $record->getWindowid())
    				$flag = true;
    			if(!$flag)
    				array_push($arrayWindows, $record);
    	}
    	foreach ($arrayWindows as $value) 
    		if ($value->getNamewindow() == $officeName) 
    			array_push($result, $value);
    	if(count($result) > 1)
    	{
    		$i =  1;
    		foreach($result as $res)
    		{
    			$res->setNamewindow($res->getNamewindow()."	($i)");
    			$i++;
    		}
    	}
    	return  $result;
    }

    /**
     * Set dropdown
     *
     * @param integer $dropdown
     * @return PersonalPage
     */
    public function setDropdown($dropdown)
    {
        $this->dropdown = $dropdown;
		
        return $this;
    }

    /**
     * Get dropdown
     *
     * @return integer 
     */
    public function getDropdown()
    {
        return $this->dropdown;
    }

    /**
     * Set dropdown
     *
     * @param integer $dropdown
     * @return PersonalPage
     */
    public static function createPersonal($userId, $officeId, $topicId, $nameWindow, $dropdown, $windowId)
    {
    	$personal = new PersonalPage();
    	$personal->setOfficeid($officeId);
    	$personal->setTopicid($topicId);
    	$personal->setUserid($userId);
    	$personal->setNamewindow($nameWindow);
    	$personal->setWindowid($windowId);
    	$personal->setDropdown($dropdown);
    	return $personal;
    }
}
