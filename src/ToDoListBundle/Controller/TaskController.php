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
     * Action to delete a task
     *
     * @param $taskId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTaskAction($taskId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository('ToDoListBundle:Task')->find($taskId);
        $entityManager->remove($task);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('todo_list_tasks_list', array('listId' => $task->getTaskListId())));
    }

    /**
     * Action to update a Task
     *
     * @param $taskId
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function updateTaskAction($taskId, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository('ToDoListBundle:Task')->find($taskId);
        if (!$task) {
            return $this->render('ToDoListBundle:TaskViews:errorPage.html.twig',
                array('errorMessage' => 'No product found for this id : ' . $taskId));
        }

        $form = $this->get('form.factory')->create(TaskType::class, $task);
        if ($form->handleRequest($request)->isValid()) {
            $data = $form->getData();
            $task->setName($data->getName());
            $task->setValue($data->getValue());
            $task->setTaskListId($data->getTaskListId());
            $entityManager->flush();
            return $this->redirect($this->generateUrl('todo_list_tasks_list', array('listId' => $task->getTaskListId())));
        }
        return $this->render('ToDoListBundle:TaskViews:updateTask.html.twig', array('form' => $form->createView(),));
    }
}