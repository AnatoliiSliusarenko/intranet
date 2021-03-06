<?php
namespace Intranet\MainBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Intranet\MainBundle\Entity\Notification;
use Intranet\MainBundle\Entity\Office;
use Intranet\MainBundle\Entity\Topic;
use Intranet\MainBundle\Entity\Task;
use Intranet\MainBundle\Entity\TaskStatus;
use Intranet\MainBundle\Entity\User;
use Intranet\MainBundle\Entity\PersonalPage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
class OfficeController extends Controller
{
    public function getOfficeMenuTreeAction()
    {
        $em = $this->getDoctrine()->getManager();

        $officeTree = Office::getOfficeTree($em);

        return $this->render("IntranetMainBundle:Office:getOfficeMenuTree.html.twig", array("officeTree" => $officeTree, "em" => $em));
    }

    public function showOfficeAction(Request $request, $office_id)
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('intranet.notifier')->clearNotificationsByOfficeId($office_id);
        $office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
        if (($office == null) || (!$office->hasUser($this->getUser())))
            return $this->redirect($this->generateUrl('intranet_main_homepage'));

        $breadcrumbs = $office->getBreadcrumbs($em);
        $users =$this->getUser()->getAllUsers($em, false);

        $officeUsers = $office->getUsers();
        $childrenOfficesForUser = $office->getChildrenForUser($em, $this->getUser());

        $topicTree = Topic::getTopicTree($em);
        $parentTopic = $topicTree[0];
        $windows = array();
        $windows = PersonalPage::getWindowsName($em, $this->getUser()->getId(), $office->getName());
        $parameters = array(
            "availableStatus" => TaskStatus::getAllStatuses($em),
            "em" => $em,
            "office" => $office,
            "parentTopic" => $parentTopic,
            "breadcrumbs" => $breadcrumbs,
            "topics" => array_map(function($e){return $e->getInArray();}, $office->getTopics()->toArray()),
            'officeUsers' => array_map(function($e){return $e->getInArray();}, $officeUsers->toArray()),
            'users' => array_map(function($e){return $e->getInArray();}, $users),
            'offices' => $childrenOfficesForUser,
            "windows" => $windows,
            'message'=>false);

        if($request->getSession())
        {
            if ($request->getSession()->has('errorOffice'))
            {
                $parameters['errorOffice'] = $request->getSession()->get('errorOffice');
                $parameters['nameOffice'] = $request->getSession()->get('nameOffice');
                $parameters['descriptionOffice'] = $request->getSession()->get('descriptionOffice');
                $request->getSession()->remove('errorOffice');
                $request->getSession()->remove('nameOffice');
                $request->getSession()->remove('descriptionOffice');
            }
            if ($request->getSession()->has('errorTopic'))
            {
                $parameters['errorTopic'] = $request->getSession()->get('errorTopic');
                $parameters['nameTopic'] = $request->getSession()->get('nameTopic');
                $parameters['descriptionTopic'] = $request->getSession()->get('descriptionTopic');
                $request->getSession()->remove('errorTopic');
                $request->getSession()->remove('nameTopic');
                $request->getSession()->remove('descriptionTopic');
            }
        }
        $fullOfficeBreadcrumbs = $breadcrumbs;
        array_push($fullOfficeBreadcrumbs, $office);
        $fullOfficeBreadcrumbsIds = array_map(function($e){return $e->getId();}, $fullOfficeBreadcrumbs);


        $this->get('twig')->addGlobal('activeSection', 'office');
        $this->get('twig')->addGlobal('fullOfficeBreadcrumbsIds', $fullOfficeBreadcrumbsIds);
        User::SetOfficeForUser($em,$this->getUser()->getId(),$office_id);
        return $this->render("IntranetMainBundle:Office:showOffice.html.twig", $parameters);
    }

    public function addOfficeAction(Request $request, $office_id)
    {
        $name = $request->request->get('name');
        $description = $request->request->get('description');
        $members = $request->request->get('members');

        //check for errors
        if ($name == '' || $description == '' || $members == null)
        {
            if ($members == null)
                $request->getSession()->set('errorOffice', 'In new office should be someone else besides you!');
            else
                $request->getSession()->set('errorOffice', 'Please fill out all fields!');

            $request->getSession()->set('nameOffice', $name);
            $request->getSession()->set('descriptionOffice', $description);

            return $this->redirect($this->generateUrl('intranet_show_office', array("office_id" => $office_id, 'message'=>false)));
        }

        $em = $this->getDoctrine()->getManager();
        $members[] = $this->getUser()->getId();

        $office = new Office();
        $office->setOfficeid($office_id);
        $office->setName($name);
        $office->setDescription($description);
        $usersAdded = $office->addUsers($em, $members);
        $office->setUserid($this->getUser()->getId());


        $em->persist($office);
        $em->flush();

        $this->get('intranet.notifier')->createNotification("membership_user", $usersAdded, $office);

        return $this->redirect($this->generateUrl('intranet_show_office', array('office_id' => $office->getId(), 'message'=>false)));
    }

    public function changeOfficeMembersAction(Request $request, $office_id)
    {
        $data = json_decode(file_get_contents("php://input"));
        $members = $data->members;
        $em = $this->getDoctrine()->getManager();

        if (($members == null) || (count($members) < 2))
        {
            $response = new Response(json_encode(array("result" => null, "message" => 'In office should be not less two members!')));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
        if ($office == null)
        {
            $response = new Response(json_encode(array("result" => null, "message" => 'Office not found!')));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        $resetUsers = $office->resetUsers($em, $members);
        $em->persist($office);
        $em->flush();

        $this->get('intranet.notifier')->createNotification("membership_user", $resetUsers['added'], $office);
        $this->get('intranet.notifier')->createNotification("membership_user_out", $resetUsers['removed'], $office);
        $response = new Response(json_encode(array("result" => true,  "message" => 'Members changed!')));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function deleteOfficeAction($office_id)
    {
        $em = $this->getDoctrine()->getManager();
        $office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
        if (($office == null) || ($office->getUserid() !== $this->getUser()->getId()))
            return $this->redirect($this->generateUrl('intranet_main_homepage'));

        $this->get('intranet.notifier')->createNotification("removed_office", $office, $office);

        $parent = $office->getOfficeid();
        $office->deleteAllChildren($em);
        $em->flush();
        return $this->redirect($this->generateUrl('intranet_show_office', array('office_id' => $parent, 'message'=>false)));
    }

    public function addOfficeChatToPersonalAction($office_id)
    {

        $em = $this->getDoctrine()->getManager();
        $office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
        $personal_office = $em->getRepository('IntranetMainBundle:PersonalPage')->findByOfficeid($office_id);
        $personal_data = $em->getRepository('IntranetMainBundle:PersonalPage')->findAll($this->getUser()->getId());
        $count_window = count($personal_data)+1;

        if(count($personal_office) != 0)
        {
            foreach ($personal_office as $value) {
                if( $value != null && $value->getTopicid() == null
                    && $value->getUserid() == $this->getUser()->getId())
                {
                    $parameters = Office::getParameters($office, $em, $this->getUser(), true);

                    return $this->render("IntranetMainBundle:Office:showOffice.html.twig",$parameters);
                }
            }
        }
        if(!$count_window)
            $window_id=1 ;
        else
            $window_id = $count_window;
        $personal = PersonalPage::createPersonal($this->getUser()->getId(),$office_id,NULL,
            $office->getName(),0,$window_id);
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($personal);
        $em->flush();
        $parameters = Office::getParameters($office, $em, $this->getUser(), false);
        return $this->render("IntranetMainBundle:Office:showOffice.html.twig",$parameters);
    }

    public function addOfficeToExistWindowPersonalPageAction($office_id, $window_id)
    {
        $em = $this->getDoctrine()->getManager();
        $office = $em->getRepository('IntranetMainBundle:Office')->find($office_id);
        $personal_office = $em->getRepository('IntranetMainBundle:PersonalPage')->findByOfficeid($office_id);
        if(count($personal_office) != 0)
        {
            foreach ($personal_office as $value) {
                if( $value != null && $value->getTopicid() == null
                    && $value->getUserid() == $this->getUser()->getId())
                {
                    $parameters = Office::getParameters($office, $em, $this->getUser(), true);
                    return $this->render("IntranetMainBundle:Office:showOffice.html.twig",$parameters);
                }
            }
        }
        $personal = PersonalPage::createPersonal($this->getUser()->getId(),$office_id,NULL,
            $office->getName(),0,$window_id);
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($personal);
        $em->flush();
        $parameters = Office::getParameters($office, $em, $this->getUser(), false);
        return $this->render("IntranetMainBundle:Office:showOffice.html.twig",$parameters);
    }
}