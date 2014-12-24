<?php

namespace Intranet\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Document
 *
 * @ORM\Table(name="user_settings_notifications")
 * @ORM\Entity
 */
class UserSettingsNotifications
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
	 * @ORM\Column(name="msg_email_message_office", type="boolean")
	 */
	private $msgEmailMessageOffice;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_email_message_topic", type="boolean")
	 */
	private $msgEmailMessageTopic;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_email_membership_own", type="boolean")
	 */
	private $msgEmailMembershipOwn;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_email_membership_user", type="boolean")
	 */
	private $msgEmailMembershipUser;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_email_removed_office", type="boolean")
	 */
	private $msgEmailRemovedOffice;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_email_removed_topic", type="boolean")
	 */
	private $msgEmailRemovedTopic;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_email_topic_added", type="boolean")
	 */
	private $msgEmailTopicAdd;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_email_membership_own_out", type="boolean")
	 */
	private $msgEmailMembershipOwnOut;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_email_membership_user_out", type="boolean")
	 */
	private $msgEmailMembershipUserOut;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_email_task_assigned", type="boolean")
	 */
	private $msgEmailTaskAssigned;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_email_task_comment", type="boolean")
	 */
	private $msgEmailTaskComment;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_site_message_office", type="boolean")
	 */
	private $msgSiteMessageOffice;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_site_message_topic", type="boolean")
	 */
	private $msgSiteMessageTopic;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_site_membership_own", type="boolean")
	 */
	private $msgSiteMembershipOwn;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_site_membership_user", type="boolean")
	 */
	private $msgSiteMembershipUser;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_site_removed_office", type="boolean")
	 */
	private $msgSiteRemovedOffice;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_site_removed_topic", type="boolean")
	 */
	private $msgSiteRemovedTopic;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_site_topic_added", type="boolean")
	 */
	private $msgSiteTopicAdd;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_site_membership_own_out", type="boolean")
	 */
	private $msgSiteMembershipOwnOut;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_site_membership_user_out", type="boolean")
	 */
	private $msgSiteMembershipUserOut;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_site_task_assigned", type="boolean")
	 */
	private $msgSiteTaskAssigned;
	
	/**
	 * @var boolean
	 * @ORM\Column(name="msg_site_task_comment", type="boolean")
	 */
	private $msgSiteTaskComment;
	
	/**
	 * @ORM\OneToOne(targetEntity="User", inversedBy="userSettingsNotifications")
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
	 * @return UserSettingsNotifications
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
	 * Set msgEmailMessageOffice
	 *
	 * @param boolean $msgEmailMessageOffice
	 * @return UserSettingsNotifications
	 */
	public function setMsgEmailMessageOffice($msgEmailMessageOffice)
	{
		$this->msgEmailMessageOffice = $msgEmailMessageOffice;
		return $this;
	}
	
	/**
     * Get msgEmailMessageOffice
     *
     * @return boolean
     */
	public function getMsgEmailMessageOffice()
	{
		return $this->msgEmailMessageOffice;
	}

	/**
	 * Set msgEmailMessageTopic
	 *
	 * @param boolean $msgEmailMessageTopic
	 * @return UserSettingsNotifications
	 */
	public function setMsgEmailMessageTopic($msgEmailMessageTopic)
	{
		$this->msgEmailMessageTopic = $msgEmailMessageTopic;
		return $this;
	}

	/**
	 * Get msgEmailMessageTopic
	 *
	 * @return boolean
	 */
	public function getMsgEmailMessageTopic()
	{
		return $this->msgEmailMessageTopic;
	}

	/**
	 * Set msgEmailMembershipOwn
	 *
	 * @param boolean $msgEmailMembershipOwn
	 * @return UserSettingsNotifications
	 */
	public function setMsgEmailMembershipOwn($msgEmailMembershipOwn)
	{
		$this->msgEmailMembershipOwn = $msgEmailMembershipOwn;
		return $this;
	}

	/**
	 * Get msgEmailMembershipOwn
	 *
	 * @return boolean
	 */
	public function getMsgEmailMembershipOwn()
	{
		return $this->msgEmailMembershipOwn;
	}

	/**
	 * Set msgEmailMembershipUser
	 *
	 * @param boolean $msgEmailMembershipUser
	 * @return UserSettingsNotifications
	 */
	public function setMsgEmailMembershipUser($msgEmailMembershipUser)
	{
		$this->msgEmailMembershipUser = $msgEmailMembershipUser;
		return $this;
	}

	/**
	 * Get msgEmailMembershipUser
	 *
	 * @return boolean
	 */
	public function getMsgEmailMembershipUser()
	{
		return $this->msgEmailMembershipUser;
	}

	/**
	 * Set msgEmailRemovedOffice
	 *
	 * @param boolean $msgEmailRemovedOffice
	 * @return UserSettingsNotifications
	 */
	public function setMsgEmailRemovedOffice($msgEmailRemovedOffice)
	{
		$this->msgEmailRemovedOffice = $msgEmailRemovedOffice;
		return $this;
	}

	/**
	 * Get msgEmailRemovedOffice
	 *
	 * @return boolean
	 */
	public function getMsgEmailRemovedOffice()
	{
		return $this->msgEmailRemovedOffice;
	}

	/**
	 * Set msgEmailRemovedTopic
	 *
	 * @param boolean $msgEmailRemovedTopic
	 * @return UserSettingsNotifications
	 */
	public function setMsgEmailRemovedTopic($msgEmailRemovedTopic)
	{
		$this->msgEmailRemovedTopic = $msgEmailRemovedTopic;
		return $this;
	}

	/**
	 * Get msgEmailRemovedTopic
	 *
	 * @return boolean
	 */
	public function getMsgEmailRemovedTopic()
	{
		return $this->msgEmailRemovedTopic;
	}

	/**
	 * Set msgEmailTopicAdd
	 *
	 * @param boolean $msgEmailTopicAdd
	 * @return UserSettingsNotifications
	 */
	public function setMsgEmailTopicAdd($msgEmailTopicAdd)
	{
		$this->msgEmailTopicAdd = $msgEmailTopicAdd;
		return $this;
	}

	/**
	 * Get msgEmailTopicAdd
	 *
	 * @return boolean
	 */
	public function getMsgEmailTopicAdd()
	{
		return $this->msgEmailTopicAdd;
	}

	/**
	 * Set msgEmailMembershipOwnOut
	 *
	 * @param boolean $msgEmailMembershipOwnOut
	 * @return UserSettingsNotifications
	 */
	public function setMsgEmailMembershipOwnOut($msgEmailMembershipOwnOut)
	{
		$this->msgEmailMembershipOwnOut = $msgEmailMembershipOwnOut;
		return $this;
	}

	/**
	 * Get msgEmailMembershipOwnOut
	 *
	 * @return boolean
	 */
	public function getMsgEmailMembershipOwnOut()
	{
		return $this->msgEmailMembershipOwnOut;
	}

	/**
	 * Set msgEmailMembershipUserOut
	 *
	 * @param boolean $msgEmailMembershipUserOut
	 * @return UserSettingsNotifications
	 */
	public function setMsgEmailMembershipUserOut($msgEmailMembershipUserOut)
	{
		$this->msgEmailMembershipUserOut = $msgEmailMembershipUserOut;
		return $this;
	}

	/**
	 * Get msgEmailMembershipUserOut
	 *
	 * @return boolean
	 */
	public function getMsgEmailMembershipUserOut()
	{
		return $this->msgEmailMembershipUserOut;
	}

	/**
	 * Set msgEmailTaskAssigned
	 *
	 * @param boolean $msgEmailTaskAssigned
	 * @return UserSettingsNotifications
	 */
	public function setMsgEmailTaskAssigned($msgEmailTaskAssigned)
	{
		$this->msgEmailTaskAssigned = $msgEmailTaskAssigned;
		return $this;
	}

	/**
	 * Get msgEmailTaskAssigned
	 *
	 * @return boolean
	 */
	public function getMsgEmailTaskAssigned()
	{
		return $this->msgEmailTaskAssigned;
	}

	/**
	 * Set msgEmailTaskComment
	 *
	 * @param boolean $msgEmailTaskComment
	 * @return UserSettingsNotifications
	 */
	public function setMsgEmailTaskComment($msgEmailTaskComment)
	{
		$this->msgEmailTaskComment = $msgEmailTaskComment;
		return $this;
	}

	/**
	 * Get msgEmailTaskComment
	 *
	 * @return boolean
	 */
	public function getMsgEmailTaskComment()
	{
		return $this->msgEmailTaskComment;
	}

	/**
	 * Set msgSiteMessageOffice
	 *
	 * @param boolean $msgSiteMessageOffice
	 * @return UserSettingsNotifications
	 */
	public function setMsgSiteMessageOffice($msgSiteMessageOffice)
	{
		$this->msgSiteMessageOffice = $msgSiteMessageOffice;
		return $this;
	}

	/**
	 * Get msgSiteMessageOffice
	 *
	 * @return boolean
	 */
	public function getMsgSiteMessageOffice()
	{
		return $this->msgSiteMessageOffice;
	}

	/**
	 * Set msgSiteMessageTopic
	 *
	 * @param boolean $msgSiteMessageTopic
	 * @return UserSettingsNotifications
	 */
	public function setMsgSiteMessageTopic($msgSiteMessageTopic)
	{
		$this->msgSiteMessageTopic = $msgSiteMessageTopic;
		return $this;
	}

	/**
	 * Get msgSiteMessageTopic
	 *
	 * @return boolean
	 */
	public function getMsgSiteMessageTopic()
	{
		return $this->msgSiteMessageTopic;
	}

	/**
	 * Set msgSiteMembershipOwn
	 *
	 * @param boolean $msgSiteMembershipOwn
	 * @return UserSettingsNotifications
	 */
	public function setMsgSiteMembershipOwn($msgSiteMembershipOwn)
	{
		$this->msgSiteMembershipOwn = $msgSiteMembershipOwn;
		return $this;
	}

	/**
	 * Get msgSiteMembershipOwn
	 *
	 * @return boolean
	 */
	public function getMsgSiteMembershipOwn()
	{
		return $this->msgSiteMembershipOwn;
	}

	/**
	 * Set msgSiteMembershipUser
	 *
	 * @param boolean $msgSiteMembershipUser
	 * @return UserSettingsNotifications
	 */
	public function setMsgSiteMembershipUser($msgSiteMembershipUser)
	{
		$this->msgSiteMembershipUser = $msgSiteMembershipUser;
		return $this;
	}

	/**
	 * Get msgSiteMembershipUser
	 *
	 * @return boolean
	 */
	public function getMsgSiteMembershipUser()
	{
		return $this->msgSiteMembershipUser;
	}

	/**
	 * Set msgSiteRemovedOffice
	 *
	 * @param boolean $msgSiteRemovedOffice
	 * @return UserSettingsNotifications
	 */
	public function setMsgSiteRemovedOffice($msgSiteRemovedOffice)
	{
		$this->msgSiteRemovedOffice = $msgSiteRemovedOffice;
		return $this;
	}

	/**
	 * Get msgSiteRemovedOffice
	 *
	 * @return boolean
	 */
	public function getMsgSiteRemovedOffice()
	{
		return $this->msgSiteRemovedOffice;
	}

	/**
	 * Set msgSiteRemovedTopic
	 *
	 * @param boolean $msgSiteRemovedTopic
	 * @return UserSettingsNotifications
	 */
	public function setMsgSiteRemovedTopic($msgSiteRemovedTopic)
	{
		$this->msgSiteRemovedTopic = $msgSiteRemovedTopic;
		return $this;
	}

	/**
	 * Get msgSiteRemovedTopic
	 *
	 * @return boolean
	 */
	public function getMsgSiteRemovedTopic()
	{
		return $this->msgSiteRemovedTopic;
	}

	/**
	 * Set msgSiteTopicAdd
	 *
	 * @param boolean $msgSiteTopicAdd
	 * @return UserSettingsNotifications
	 */
	public function setMsgSiteTopicAdd($msgSiteTopicAdd)
	{
		$this->msgSiteTopicAdd = $msgSiteTopicAdd;
		return $this;
	}

	/**
	 * Get msgSiteTopicAdd
	 *
	 * @return boolean
	 */
	public function getMsgSiteTopicAdd()
	{
		return $this->msgSiteTopicAdd;
	}

	/**
	 * Set msgSiteMembershipOwnOut
	 *
	 * @param boolean $msgSiteMembershipOwnOut
	 * @return UserSettingsNotifications
	 */
	public function setMsgSiteMembershipOwnOut($msgSiteMembershipOwnOut)
	{
		$this->msgSiteMembershipOwnOut = $msgSiteMembershipOwnOut;
		return $this;
	}

	/**
	 * Get msgSiteMembershipOwnOut
	 *
	 * @return boolean
	 */
	public function getMsgSiteMembershipOwnOut()
	{
		return $this->msgSiteMembershipOwnOut;
	}

	/**
	 * Set msgSiteMembershipUserOut
	 *
	 * @param boolean $msgSiteMembershipUserOut
	 * @return UserSettingsNotifications
	 */
	public function setMsgSiteMembershipUserOut($msgSiteMembershipUserOut)
	{
		$this->msgSiteMembershipUserOut = $msgSiteMembershipUserOut;
		return $this;
	}

	/**
	 * Get msgSiteMembershipUserOut
	 *
	 * @return boolean
	 */
	public function getMsgSiteMembershipUserOut()
	{
		return $this->msgSiteMembershipUserOut;
	}

	/**
	 * Set msgSiteTaskAssigned
	 *
	 * @param boolean $msgSiteTaskAssigned
	 * @return UserSettingsNotifications
	 */
	public function setMsgSiteTaskAssigned($msgSiteTaskAssigned)
	{
		$this->msgSiteTaskAssigned = $msgSiteTaskAssigned;
		return $this;
	}

	/**
	 * Get msgSiteTaskAssigned
	 *
	 * @return boolean
	 */
	public function getMsgSiteTaskAssigned()
	{
		return $this->msgSiteTaskAssigned;
	}

	/**
	 * Set msgSiteTaskComment
	 *
	 * @param boolean $msgSiteTaskComment
	 * @return UserSettingsNotifications
	 */
	public function setMsgSiteTaskComment($msgSiteTaskComment)
	{
		$this->msgSiteTaskComment = $msgSiteTaskComment;
		return $this;
	}

	/**
	 * Get msgSiteTaskComment
	 *
	 * @return boolean
	 */
	public function getMsgSiteTaskComment()
	{
		return $this->msgSiteTaskComment;
	}
	
	/**
	 * Set user
	 *
	 * @param \Intranet\MainBundle\Entity\User $user
	 * @return UserSettingsNotifications
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
				'msg_email_message_office' => $this->getMsgSiteMessageOffice(),
				'msg_email_message_topic' => $this->getMsgSiteMessageTopic(),
				'msg_email membership_own' => $this->getMsgSiteMembershipOwn(),
				'msg_email_membership_user' => $this->getMsgSiteMembershipUser(),
				'msg_email_removed_office' => $this->getMsgSiteRemovedOffice(),
				'msg_email_removed_topic' => $this->getMsgSiteRemovedTopic(),
				'msg_email_topic_added' => $this->getMsgSiteTopicAdd(),
				'msg_email_membership_own_out' => $this->getMsgSiteMembershipOwnOut(),
				'msg_email_membership_user_out' => $this->getMsgSiteMembershipUserOut(),
				'msg_email_task_assigned' => $this->getMsgSiteTaskAssigned(),
				'msg_email_task_comment' => $this->getMsgSiteTaskComment(),
				'msg_site_message_office' => $this->getMsgSiteMessageOffice(),
				'msg_site_message_topic' => $this->getMsgSiteMessageTopic(),
				'msg_site_ membership_own' => $this->getMsgSiteMembershipOwn(),
				'msg_site_membership_user' => $this->getMsgSiteMembershipUser(),
				'msg_site_removed_office' => $this->getMsgSiteRemovedOffice(),
				'msg_site_removed_topic' => $this->getMsgSiteRemovedTopic(),
				'msg_site_topic_added' => $this->getMsgSiteTopicAdd(),
				'msg_site_membership_own_out' => $this->getMsgSiteMembershipOwnOut(),
				'msg_site_membership_user_out' => $this->getMsgSiteMembershipUserOut(),
				'msg_site_task_assigned' => $this->getMsgSiteTaskAssigned(),
				'msg_site_task_comment' => $this->getMsgSiteTaskComment()
		);
	}

}
