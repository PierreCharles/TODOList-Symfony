<?php

namespace ToDoListBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use \DateTime;

use ToDoListBundle\Entity\TaskList;
use ToDoListBundle\Entity\Task;
use ToDoListBundle\Form\TaskListType;
use ToDoListBundle\Form\TaskType;


class TaskListController extends Controller
{

    public function indexAction()
    {
        return $this->redirect($this->generateUrl("todo_list_detail_task_list"));
    }

    public function addTaskListAction(Request $request)
    {
        $taskList = new TaskList();
        $form = $this->get('form.factory')->create(TaskListType::class, $taskList);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($taskList);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'TaskList saved.');
            return $this->redirect($this->generateUrl('todo_list_add_task_list'));
        }
        return $this->render('ToDoListBundle:TaskViews:addTaskList.html.twig', array('form' => $form->createView(),));
    }


    public function detailTasksAction($idList, Request $request)
    {
        $taskList = $this->getDoctrine()->getRepository('ToDoListBundle:TaskList')->find($idList);
        $repository = $this->getDoctrine()->getRepository('ToDoListBundle:Task');
        $tasks = $repository->findByTaskListID($idList);

        $task = new Task();
        $task->setTaskListID($idList);
        $form = $this->get('form.factory')->create(TaskType::class, $task);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Task saved.');
            return $this->redirect($this->generateUrl('todo_list_detail_tasks', array('idList' => $idList)));
        }

        return $this->render('ToDoListBundle:TaskViews:detailTasks.html.twig', array('tasks' => $tasks, 'taskList' => $taskList, 'form' => $form->createView(),));

    }

    public function detailTaskListAction()
    {
        $repository = $this->getDoctrine()->getRepository('ToDoListBundle:TaskList');
        $tasklists = $repository->findAll();

        if (!$tasklists) {
            throw $this->createNotFoundException(
                'No tasklist found.'
            );
        }
        return $this->render('ToDoListBundle:TaskViews:index.html.twig', array('tasklists' => $tasklists));
    }


    public function deleteTaskListAction($idList)
    {
        $em = $this->getDoctrine()->getManager();
        $TaskList = $em->getRepository('ToDoListBundle:TaskList')->find($idList);
        $em->remove($TaskList);
        $em->flush();
        return $this->redirect($this->generateUrl('todo_list_detail_task_list'));
    }


    public function updateTaskListAction($idList, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $taskList = $em->getRepository('ToDoListBundle:TaskList')->find($idList);

        if (!$taskList) {
            throw $this->createNotFoundException(
                'No product found for id ' . $idList
            );
        }

        $form = $this->get('form.factory')->create(TaskListType::class, $taskList);

        if ($form->handleRequest($request)->isValid()) {
            $data = $form->getData();
            $taskList->setName($data->getName());
            $em->flush();

            return $this->redirect($this->generateUrl("detail"));
        }

        return $this->render('ToDoListBundle:TaskViews:updateTaskList.html.twig', array('form' => $form->createView(),));


    }


}
