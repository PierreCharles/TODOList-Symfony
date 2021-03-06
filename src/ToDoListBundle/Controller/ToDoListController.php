<?php

namespace ToDoListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use ToDoListBundle\Entity\TaskList;
use ToDoListBundle\Form\TaskListType;
use ToDoListBundle\Entity\Task;
use ToDoListBundle\Form\TaskType;

class ToDoListController extends Controller
{
    /**
     * Index
     *
     * @return Response
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('ToDoListBundle:TaskList');
        $tasksList = $repository->findAll();
        return $this->render('ToDoListBundle:TaskViews:index.html.twig', array('tasksLists' => $tasksList));
    }

    /**
     * Add a TasksList
     *
     * @param Request $request
     *
     * @return RedirectResponse | Response
     */
    public function addTasksListAction(Request $request)
    {
        $taskList = new TaskList();
        $form = $this->get('form.factory')->create(TaskListType::class, $taskList);
        if ($form->handleRequest($request)->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($taskList);
            $entityManager->flush();
            $request->getSession()->getFlashBag()->add('notice', 'TaskList added.');
            return $this->redirect($this->generateUrl('todo_list_homepage'));
        }
        return $this->render('ToDoListBundle:TaskViews:addTasksList.html.twig', array('form' => $form->createView(),));
    }

    /**
     * Display the details of a TasksList
     *
     * @param $listId
     * @param Request $request
     *
     * @return RedirectResponse | Response
     */
    public function listTasksAction($listId, Request $request)
    {
        $taskList = $this->getDoctrine()->getRepository('ToDoListBundle:TaskList')->find($listId);
        $repository = $this->getDoctrine()->getRepository('ToDoListBundle:Task');
        $tasks = $repository->findByTaskListId($listId);

        $task = new Task();
        $task->setTaskListId($listId);
        $form = $this->get('form.factory')->create(TaskType::class, $task);

        if ($form->handleRequest($request)->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Task saved.');
            return $this->redirect($this->generateUrl('todo_list_tasks_list', array('listId' => $listId)));
        }
        return $this->render('ToDoListBundle:TaskViews:listTasks.html.twig', array('tasks' => $tasks, 'taskList' => $taskList, 'form' => $form->createView(),));
    }

    /**
     *  display the tasks list
     *
     * @return Response
     */
    public function listTaskListAction()
    {
        $repository = $this->getDoctrine()->getRepository('ToDoListBundle:TaskList');
        $taskLists = $repository->findAll();

        if (!$taskLists) {
            return $this->render('ToDoListBundle:TaskViews:errorPage.html.twig',
                array('errorMessage' => 'No task list found'));
        }
        return $this->render('ToDoListBundle:TaskViews:index.html.twig', array('tasklists' => $taskLists));
    }

    /**
     * Delete a tasksList
     *
     * @param $listId
     *
     * @return RedirectResponse
     */
    public function deleteTasksListAction($listId)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TaskList = $entityManager->getRepository('ToDoListBundle:TaskList')->find($listId);
        $entityManager->remove($TaskList);
        $entityManager->flush();
        return $this->redirect($this->generateUrl('todo_list_homepage'));
    }

    /**
     * Update a tasksList with id
     *
     * @param $listId
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateTasksListAction($listId, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $taskList = $entityManager->getRepository('ToDoListBundle:TaskList')->find($listId);

        if (!$taskList) {
            return $this->render('ToDoListBundle:TaskViews:errorPage.html.twig',
                array('errorMessage' => 'No tasks list found for id : ' . $listId));
        }

        $form = $this->get('form.factory')->create(TaskListType::class, $taskList);

        if ($form->handleRequest($request)->isValid()) {
            $data = $form->getData();
            $taskList->setName($data->getName());
            $entityManager->flush();
            return $this->redirect($this->generateUrl("todo_list_homepage"));
        }
        return $this->render('ToDoListBundle:TaskViews:updateTaskList.html.twig', array('form' => $form->createView(),));
    }

}