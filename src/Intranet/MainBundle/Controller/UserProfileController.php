<?php

namespace Intranet\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Intranet\MainBundle\Entity\Document;

class UserProfileController extends Controller
{
    public function showAction()
    {
    	$timestamp = time();
    	$user = $this->getUser();
    	$token = Document::getToken($timestamp); 
        return $this->render('IntranetMainBundle:UserProfile:showProfile.html.twig',array('timestamp' => $timestamp,
        			  'token' => $token,
        			  'availableTypes'=> Document::getAvailableTypesInString()
        ));
    }
    
    public function changeAvatarAction($document_id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$document = $em->getRepository('IntranetMainBundle:Document')->find($document_id);
    	
    	$this->getUser()->setAvatar($document->getName());
    	$em->persist($this->getUser());
    	$em->flush();
    	
    	return $this->redirect($this->generateUrl('intranet_user_profile'));
    }
    
    public function changeSettingsAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();
    	$settings = $this->getUser()->getUserSettings();
    	$settingsNotifications = $this->getUser()->getUserSettingsNotifications();

    	$showHiddenTopics = $request->request->get('showHiddenTopics');
    	
    	if ($showHiddenTopics == null)
    		$settings->setShowHiddenTopics(false);
    	else
    		$settings->setShowHiddenTopics(true);
    	
    	$disableAllOnEmail = $request->request->get('disableAllOnEmail');
    	 
    	if ($disableAllOnEmail == null)
    		$settings->setDisableAllOnEmail(false);
    	else
    		$settings->setDisableAllOnEmail(true);
    	
    	$disableAllOnSite = $request->request->get('disableAllOnSite');
    	
    	if ($disableAllOnSite == null)
    		$settings->setDisableAllOnSite(false);
    	else
    		$settings->setDisableAllOnSite(true);
    	
    	//--- Settings Notifications ---
    	//-----------------
    	$msgEmailMessageOffice = $request->request->get('msgEmailMessageOffice');
    	
    	if ($msgEmailMessageOffice == null)
    		$settingsNotifications->setMsgEmailMessageOffice(false);
    	else
    		$settingsNotifications->setMsgEmailMessageOffice(true);

    	//-----------------
    	$msgEmailMessageTopic = $request->request->get('msgEmailMessageTopic');
    	
    	if ($msgEmailMessageTopic == null)
    		$settingsNotifications->setMsgEmailMessageTopic(false);
    	else
    		$settingsNotifications->setMsgEmailMessageTopic(true);

    	//-----------------
    	$msgEmailMembershipOwn = $request->request->get('msgEmailMembershipOwn');
    	
    	if ($msgEmailMembershipOwn == null)
    		$settingsNotifications->setMsgEmailMembershipOwn(false);
    	else
    		$settingsNotifications->setMsgEmailMembershipOwn(true);

    	//-----------------
    	$msgEmailMembershipUser = $request->request->get('msgEmailMembershipUser');
    	
    	if ($msgEmailMembershipUser == null)
    		$settingsNotifications->setMsgEmailMembershipUser(false);
    	else
    		$settingsNotifications->setMsgEmailMembershipUser(true);

    	//-----------------
    	$msgEmailRemovedOffice = $request->request->get('msgEmailRemovedOffice');
    	
    	if ($msgEmailRemovedOffice == null)
    		$settingsNotifications->setMsgEmailRemovedOffice(false);
    	else
    		$settingsNotifications->setMsgEmailRemovedOffice(true);

    	//-----------------
    	$msgEmailRemovedTopic = $request->request->get('msgEmailRemovedTopic');
    	
    	if ($msgEmailRemovedTopic == null)
    		$settingsNotifications->setMsgEmailRemovedTopic(false);
    	else
    		$settingsNotifications->setMsgEmailRemovedTopic(true);

    	//-----------------
    	$msgEmailTopicAdd = $request->request->get('msgEmailTopicAdd');
    	
    	if ($msgEmailTopicAdd == null)
    		$settingsNotifications->setMsgEmailTopicAdd(false);
    	else
    		$settingsNotifications->setMsgEmailTopicAdd(true);

    	//-----------------
    	$msgEmailMembershipOwnOut = $request->request->get('msgEmailMembershipOwnOut');
    	
    	if ($msgEmailMembershipOwnOut == null)
    		$settingsNotifications->setMsgEmailMembershipOwnOut(false);
    	else
    		$settingsNotifications->setMsgEmailMembershipOwnOut(true);

    	//-----------------
    	$msgEmailMembershipUserOut = $request->request->get('msgEmailMembershipUserOut');
    	
    	if ($msgEmailMembershipUserOut == null)
    		$settingsNotifications->setMsgEmailMembershipUserOut(false);
    	else
    		$settingsNotifications->setMsgEmailMembershipUserOut(true);

    	//-----------------
    	$msgEmailTaskAssigned = $request->request->get('msgEmailTaskAssigned');
    	
    	if ($msgEmailTaskAssigned == null)
    		$settingsNotifications->setMsgEmailTaskAssigned(false);
    	else
    		$settingsNotifications->setMsgEmailTaskAssigned(true);

    	//-----------------
    	$msgEmailTaskComment = $request->request->get('msgEmailTaskComment');
    	
    	if ($msgEmailTaskComment == null)
    		$settingsNotifications->setMsgEmailTaskComment(false);
    	else
    		$settingsNotifications->setMsgEmailTaskComment(true);

    	//----------------- 111
    	$msgSiteMessageOffice = $request->request->get('msgSiteMessageOffice');
    	
    	if ($msgSiteMessageOffice == null)
    		$settingsNotifications->setMsgSiteMessageOffice(false);
    	else
    		$settingsNotifications->setMsgSiteMessageOffice(true);

    	//-----------------
    	$msgSiteMessageTopic = $request->request->get('msgSiteMessageTopic');
    	
    	if ($msgSiteMessageTopic == null)
    		$settingsNotifications->setMsgSiteMessageTopic(false);
    	else
    		$settingsNotifications->setMsgSiteMessageTopic(true);

    	//-----------------
    	$msgSiteMembershipOwn = $request->request->get('msgSiteMembershipOwn');
    	
    	if ($msgSiteMembershipOwn == null)
    		$settingsNotifications->setMsgSiteMembershipOwn(false);
    	else
    		$settingsNotifications->setMsgSiteMembershipOwn(true);

    	//-----------------
    	$msgSiteMembershipUser = $request->request->get('msgSiteMembershipUser');
    	
    	if ($msgSiteMembershipUser == null)
    		$settingsNotifications->setMsgSiteMembershipUser(false);
    	else
    		$settingsNotifications->setMsgSiteMembershipUser(true);

    	//-----------------
    	$msgSiteRemovedOffice = $request->request->get('msgSiteRemovedOffice');
    	
    	if ($msgSiteRemovedOffice == null)
    		$settingsNotifications->setMsgSiteRemovedOffice(false);
    	else
    		$settingsNotifications->setMsgSiteRemovedOffice(true);

    	//-----------------
    	$msgSiteRemovedTopic = $request->request->get('msgSiteRemovedTopic');
    	
    	if ($msgSiteRemovedTopic == null)
    		$settingsNotifications->setMsgSiteRemovedTopic(false);
    	else
    		$settingsNotifications->setMsgSiteRemovedTopic(true);

    	//-----------------
    	$msgSiteTopicAdd = $request->request->get('msgSiteTopicAdd');
    	
    	if ($msgSiteTopicAdd == null)
    		$settingsNotifications->setMsgSiteTopicAdd(false);
    	else
    		$settingsNotifications->setMsgSiteTopicAdd(true);

    	//-----------------
    	$msgSiteMembershipOwnOut = $request->request->get('msgSiteMembershipOwnOut');
    	
    	if ($msgSiteMembershipOwnOut == null)
    		$settingsNotifications->setMsgSiteMembershipOwnOut(false);
    	else
    		$settingsNotifications->setMsgSiteMembershipOwnOut(true);

    	//-----------------
    	$msgSiteMembershipUserOut = $request->request->get('msgSiteMembershipUserOut');
    	
    	if ($msgSiteMembershipUserOut == null)
    		$settingsNotifications->setMsgSiteMembershipUserOut(false);
    	else
    		$settingsNotifications->setMsgSiteMembershipUserOut(true);

    	//-----------------
    	$msgSiteTaskAssigned = $request->request->get('msgSiteTaskAssigned');
    	
    	if ($msgSiteTaskAssigned == null)
    		$settingsNotifications->setMsgSiteTaskAssigned(false);
    	else
    		$settingsNotifications->setMsgSiteTaskAssigned(true);

    	//-----------------
    	$msgSiteTaskComment = $request->request->get('msgSiteTaskComment');
    	
    	if ($msgSiteTaskComment == null)
    		$settingsNotifications->setMsgSiteTaskComment(false);
    	else
    		$settingsNotifications->setMsgSiteTaskComment(true);

    	//--- End of Settings Notifications ---
    	$em->persist($settings);
    	$em->persist($settingsNotifications);
    	$em->flush();
    	
    	return $this->redirect($this->generateUrl('intranet_user_profile'));
    }
    
}
