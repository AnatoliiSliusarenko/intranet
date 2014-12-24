<?php
namespace Intranet\MainBundle\Controller;
use Intranet\MainBundle\Entity\Sprint;
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
            $sprint = new Sprint();
            $sprint->setName($sprintjs->name);
            $sprint->setDescription($sprintjs->description);
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
        $tasks = $em->getRepository('IntranetMainBundle:Task')->findBysprintid($sprintid);
        $parameters = array(
            "sprint"=>$sprint,
            "tasks"=>$tasks,
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
}