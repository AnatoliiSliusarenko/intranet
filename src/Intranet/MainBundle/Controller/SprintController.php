<?php
namespace Intranet\MainBundle\Controller;
use Intranet\MainBundle\Entity\Sprint;
use Intranet\MainBundle\Entity\SprintStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
class SprintController extends Controller
{
    public function getSprintsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $spr = array();
        $sprints = $em->getRepository('IntranetMainBundle:Sprint')->findAll();
        foreach($sprints as $sprint)
            if($sprint->getStatusid() == 1 || $sprint->getStatusid() == 2)
                array_push($spr,$sprint);
        return $this->render("IntranetMainBundle:Sprint:getSprints.html.twig",array("sprints"=>$spr));
    }

    public function createSprintAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == 'POST') {
            $data = json_decode(file_get_contents("php://input"));
            $sprintjs = (object)$data;
            $status = $em->getRepository('IntranetMainBundle:SprintStatus')->find(1);
            $sprint = new Sprint();
            $sprint->setName($sprintjs->name);
            $sprint->setDescription($sprintjs->description);
            $sprint->setStatusid(1);
            $sprint->setStatus($status);
            $em->persist($sprint);
            $em->flush();
            $response = new Response(json_encode(array("result" => $sprint->getInArray())));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        return $this->render('IntranetMainBundle:Sprint:addSprint.html.twig');
    }

    public function showSprintAction($sprintid)
    {
        $em = $this->getDoctrine()->getManager();
        $sprint = $em->getRepository('IntranetMainBundle:Sprint')->find($sprintid);
        $status = $em->getRepository('IntranetMainBundle:SprintStatus')->find($sprint->getStatusid());
        $tasks = $em->getRepository('IntranetMainBundle:Task')->findBysprintid($sprintid);
        $parameters = array(
            "sprint"=>$sprint,
            "tasks"=>$tasks,
            "status"=>$status,
            "em"=>$em
        );
        return $this->render("IntranetMainBundle:Sprint:showSprint.html.twig",$parameters);
    }

    public function addTaskToSprintAction(Request $request, $taskid)
    {
        if ($request->getMethod() == 'POST') {
            $data = json_decode(file_get_contents("php://input"));
            $dat = (object)$data;
            $sprintid = $dat->sprintid;
            $em = $this->getDoctrine()->getManager();
            $sprint = $em->getRepository('IntranetMainBundle:Sprint')->find($sprintid);
            $task  = $em->getRepository('IntranetMainBundle:Task')->find($taskid);
            if($task->getSprintid()!= null)
            {
                $response = new Response(json_encode(array("message" => "This task already added in sprint")));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
            $sprint->addTask($task);
            $task->setSprintid($sprintid);
            $task->setSprint($sprint);
            foreach($task->getSubTasks($em) as $subtask)
            {
                $subtask->setSprintid($sprintid);
                $subtask->setSprint($sprint);
                $sprint->addTask($subtask);
                $em->persist($subtask);
            }
            $em->flush();
            $response = new Response(json_encode(array("result" => $sprint->getInArray())));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        $em = $this->getDoctrine()->getManager();
        $sprints = $em->getRepository('IntranetMainBundle:Sprint')->findAll();
        $parameters = array(
            "sprints"=>$sprints,
            "taskid"=>$taskid
        );
        return $this->render("IntranetMainBundle:Sprint:addTaskToSprint.html.twig",$parameters);
    }

    public function changeSprintStatusAction($sprintid)
    {
        $em = $this->getDoctrine()->getManager();
        $sprint = $em->getRepository('IntranetMainBundle:Sprint')->find($sprintid);
        if($sprint->getStatusid() == 1)
            $status = $em->getRepository('IntranetMainBundle:SprintStatus')->find(2);
        else
            $status = $em->getRepository('IntranetMainBundle:SprintStatus')->find(3);
        $sprint->setStatus($status);
        $sprint->setStatusid($status->getId());
        $em->flush();
        $response = new Response(json_encode(array("message" => "ok")));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}