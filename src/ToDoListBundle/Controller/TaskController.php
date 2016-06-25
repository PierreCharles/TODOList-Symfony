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
    /**
     * Action to get a Task with his Id
     *
     * @param $idTask
     * @param Request $request
     *
     * @return Response
     */
    public function getTaskAction($idTask, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository('ToDoListBundle:Task')->find($idTask);
        return $this->render('ToDoListBundle:TaskViews:getTask.html.twig', array('task' => $task));
    }

    /**
     * Action to delete a task
     *
     * @param $idTask
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTaskAction($idTask)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository('ToDoListBundle:Task')->find($idTask);
        $entityManager->remove($task);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('todo_list_detail_tasks', array('idList' => $task->getTaskListID())));
    }

    /**
     * Action to update a Task
     *
     * @param $idTask
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function updateTaskAction($idTask, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository('ToDoListBundle:Task')->find($idTask);
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
            $entityManager->flush();
            return $this->redirect($this->generateUrl('todo_list_get_task', array('idTask' => $idTask)));
        }
        return $this->render('ToDoListBundle:TaskViews:updateTask.html.twig', array('form' => $form->createView(),));
    }
}