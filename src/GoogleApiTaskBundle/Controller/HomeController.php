<?php

namespace GoogleApiTaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\HttpFoundation\Request;

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
            $accessGoogle = json_decode($accessToken);

            // Pour pouvoir obtenir le refresh token
            $refreshToken = json_decode(file_get_contents(__DIR__."/../tokenRefresh.txt"));
            $refreshToken[$accessGoogle['access_token']] = $accessGoogle['refresh_token'];

            file_put_contents(json_encode($refreshToken));

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

        return $this->render('GoogleApiTaskBundle:Home:items.html.twig', array('tasks' => $tasks, 'taskList' => $taskLists));
    }

}
