<?php
namespace ToDoListBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ToDoListBundle\Entity\Task;
use ToDoListBundle\Form\TaskType;

class TaskController extends Controller
{

    public function getTaskAction($idTask, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('ToDoListBundle:Task')->find($idTask);
        return $this->render('ToDoListBundle:TaskViews:getTask.html.twig', array('task' => $task,));
    }


    public function deleteTaskAction($idTask)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('ToDoListBundle:Task')->find($idTask);
        $em->remove($task);
        $em->flush();
        return $this->redirect($this->generateUrl('todo_list_detail_tasks', array('idList' => $task->getTaskListID())));
    }


    public function updateTaskAction($idTask, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('ToDoListBundle:Task')->find($idTask);
        if (!$task) {
            throw $this->createNotFoundException(
                'No product found for id ' . $idTask
            );
        }

        $form = $this->get('form.factory')->create(TaskType::class, $task);
        if ($form->handleRequest($request)->isValid()) {
            $data = $form->getData();
            $task->setName($data->getName());
            $task->setStatut($data->getStatut());
            $task->setTaskListID($data->getTaskListID());
            $em->flush();
            return $this->redirect($this->generateUrl('todo_list_get_task', array('idTask' => $idTask)));
        }
        return $this->render('ToDoListBundle:TaskViews:updateTask.html.twig', array('form' => $form->createView(),));

    }

}