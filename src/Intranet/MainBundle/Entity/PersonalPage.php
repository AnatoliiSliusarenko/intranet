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
			return $this->redirect($this->generateUrl('intranet_main_homepage'));
    	foreach ($personal_data as $personal_record)
    	{
    		array_push($windows_id_arr, $personal_record->getWindowid());
    		if(array_search($personal_record->getOfficeId(), $office_id_arr) == false)
    			array_push($office_id_arr,$personal_record->getOfficeId());
    		if(array_search($personal_record->getTopicId(), $office_id_arr) == false)
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
    		array_push($windows, $window = $em->getRepository('IntranetMainBundle:PersonalPage')->findOneByWindowid($window_id));
    	}
    	$parameters = array (
    			"topics" => $topics,
    			"em" => $em,
    			"offices" => $offices,
    			"windows" => $windows
    	);
    	return $parameters;
    }
    
    public static function getTopicsForWindow($officeid, $em, $topics)
    {
    	$topics_id = $em->getRepository('IntranetMainBundle:PersonalPage')->findByWindowid($officeid);
    	$window_topics = array();
    	foreach ($topics as $topic)
    	{
    		foreach ($topics_id as $window_topic_id)
    		{
    			if($topic->getId() == $window_topic_id->getTopicid())
    				array_push($window_topics, $topic);
    			else
    				continue;
    		}
    	}
    	return $window_topics;
    }
    
    public static function getOfficeForWindow($em, $window)
    {
    	$office = $em->getRepository('IntranetMainBundle:PersonalPage')->findByTopicid(NULL);
    	if($office[0]->getWindowid()==$window->getWindowid())
    		return $office;
    	else return NULL;
    }
    
    public static function getAllIdForUser($em, $userid)
    {
    	$office_id_arr = array();
    	$topic_id_arr = array();
    	$windows = array();
    	$windows_id_arr = array();
    	$personal_data = $em->getRepository('IntranetMainBundle:PersonalPage')->findAll($userid);
    	if ($personal_data == null)
    		return $this->redirect($this->generateUrl('intranet_main_homepage'));
    	foreach ($personal_data as $personal_record)
    	{
    		array_push($windows_id_arr, $personal_record->getWindowid());
    		if(array_search($personal_record->getTopicId(), $office_id_arr) == false)
    		if($personal_record->getTopicId()!= null)
    			array_push($topic_id_arr,$personal_record->getTopicId());
    		 
    	}
    	$windows_id_arr = array_unique($windows_id_arr);
    	foreach ($windows_id_arr as $window_id)
    		array_push($windows, $window = $em->getRepository('IntranetMainBundle:PersonalPage')->findOneByWindowid($window_id));
    	foreach ($windows as $window)
    		if($window->getTopicid() == NULL)
    			array_push($office_id_arr, $window->getOfficeid());
    	$result_array = array(
    		"officesid" => $office_id_arr,
    		"topicsid" => $topic_id_arr
    	);
    	return $result_array;
    }
    
    public static function getWindowsName($em)
    {
    	$arrayWindows = array();
    	$variable = 
    	$records = $em->getRepository('IntranetMainBundle:PersonalPage')->findAll();
    	foreach ($records as $record) {
    		$variable = array(
    			"windowName" =>  $record->getNamewindow(),
    			"windowId" => $record->getWindowid()
    		);
    		$var = (object) $variable;
    		array_push($arrayWindows, $variable);
    	}
    	return  $arrayWindows;
    }
}
