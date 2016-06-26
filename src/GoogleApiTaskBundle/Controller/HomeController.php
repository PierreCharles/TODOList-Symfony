<?php

namespace GoogleApiTaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\HttpFoundation\Request;
use Google_Service_Tasks_Task;
use Google_Service_Tasks_TaskList;

class HomeController extends Controller
{
    /**
     * index Action to have the menu for the google TodoList
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('GoogleApiTaskBundle:Home:index.html.twig');
    }

    /**
     * Callback Function for Oauth authorization with google API, set the Token for a user
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function callbackAction(Request $request)
    {
        if ($request->query->get('error')) {
            return $this->render('GoogleApiTaskBundle:Home:errorGoogle.html.twig');
        }

        if ($request->query->get('code')) {
            $client = $this->get('happyr.google.api.client');

            $client->authenticate($request->query->get('code'));

            $accessToken = $client->getAccessToken();

            // save the refresh token in a file, ( don't work because the API doesn't return a refresh token )
            $accessGoogle = json_decode($accessToken['access_token'],true);
            $refreshToken = json_decode(file_get_contents(__DIR__."/../tokenRefresh.txt"), true);
            $refreshToken[$accessGoogle['access_token']] = $accessGoogle['refresh_token'];
            file_put_contents(__DIR__."/../tokenRefresh.txt", json_encode($refreshToken));


            $security = $this->get('security.token_storage');

            $token = $security->getToken();
            $token = new PreAuthenticatedToken(
                json_encode($accessToken), null, $token->getProviderKey(), ['ROLE_OK']
            );

            $security->setToken($token);
        }
        return $this->redirectToRoute('google_task_api_list');
    }

    /**
     * Logout Action, remove the token
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function logoutAction()
    {
        $securityStorage = $this->get('security.token_storage');
        $securityStorage->setToken(null);

        return $this->redirectToRoute('homepage');
    }

    /**
     * Method to list all the Task from the Google API Tasks
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $taskService = $this->get('google_task_api.google.home');
        $taskLists = $taskService->getTaskLists();

        return $this->render('GoogleApiTaskBundle:Home:index.html.twig', array('taskLists' => $taskLists));
    }

    /**
     * Methode to delete a taskList to Google API Tasks
     *
     * @param $taskList
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($taskList)
    {
        $taskService = $this->get('google_task_api.google.home');
        $taskService->deleteTasksList($taskList);

        return $this->redirectToRoute('google_task_api_list');
    }

    /**
     * Method to update a task List
     *
     * @param $taskList
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction($taskList)
    {
        return $this->render('GoogleApiTaskBundle:Home:update.html.twig');
    }


    /**
     * Action to print the taskList items from google API Tasks
     *
     * @param $taskList
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showItemsAction($taskList)
    {
        $taskService = $this->get('google_task_api.google.home');
        $tasks = $taskService->getTasksFromList($taskList);
        $taskLists = $taskService->getTaskLists($taskList);

        return $this->render('GoogleApiTaskBundle:Home:items.html.twig', array('tasks' => $tasks, 'taskList' => $taskLists, 'idTaskList' => $taskList));
    }

    /**
     * Method to add a taskList in google API
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addTaskListAction(Request $request)
    {
        $title = htmlspecialchars($request->request->get("title"));

        $taskList = new Google_Service_Tasks_TaskList();
        $taskList->setTitle($title);

        $this->get('google_task_api.google.home')->addTaskList($taskList);

        return $this->redirectToRoute('google_task_api_list');
    }

    /**
     * Method to add a task in a TaskList, Ajax call
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addTaskInTaskListsAction(Request $request)
    {
        $title = $request->request->get("title");
        $idTaskList = $request->request->get("idTaskLists");
        $task = new Google_Service_Tasks_Task();

        $task->setTitle($title);
        
        $this->get('google_task_api.google.home')->addTaskInTaskLists($idTaskList, $task);

        return new JsonResponse(array('status'=> 'OK'));
    }


}
