<?php

namespace GoogleApiTaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\User;

class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('GoogleApiTaskBundle:Default:index.html.twig');
    }

    public function simpleAction()
    {
        return $this->render('GoogleApiTaskBundle:Default:index.html.twig');
    }

    public function callbackAction(Request $request)
    {

        if ($request->query->get('error')) {
            return $this->render('GoogleApiTaskBundle:Default:index.html.twig');
        }

        if ($request->query->get('code')) {
            $client = $this->get('happyr.google.api.client');

            $client->authenticate($request->query->get('code'));

            $accessToken = $client->getAccessToken();
            $security = $this->get('security.token_storage');

            $token = $security->getToken();
            $token = new PreAuthenticatedToken(
                json_encode($accessToken), null, $token->getProviderKey(), ['ROLE_OK']
            );

            $security->setToken($token);
        }
        return $this->redirectToRoute('google_task_api_list');
    }


    public function logoutAction()
    {

        $securityStorage = $this->get('security.token_storage');
        $securityStorage->setToken(null);

        return $this->redirectToRoute('homepage');
    }

    public function listAction()
    {
        $taskService = $this->get('google_task_api.google.home');
        $taskLists = $taskService->getTaskLists();

        foreach ($taskLists as $taskList) {
            $tasks = $taskService->getTasksFromList($taskList->getId());
        }

        return $this->render('GoogleApiTaskBundle:Default:index.html.twig', array('taskLists' => $taskLists));
    }

    public function deleteAction($taskList)
    {
        $taskService = $this->get('google_task_api.google.home');
        $taskService->deleteTasksList($taskList);
        return $this->redirectToRoute('google_task_api_list');
    }

    public function updateAction($taskList)
    {
        return $this->render('GoogleApiTaskBundle:Default:update.html.twig');
    }


    public function showItemsAction($taskList)
    {
        $taskService = $this->get('google_task_api.google.home');
        $tasks = $taskService->getTasksFromList($taskList);
        $taskLists = $taskService->getTaskLists($taskList);

        return $this->render('GoogleApiTaskBundle:Default:items.html.twig', array('tasks' => $tasks, 'taskList' => $taskLists));
    }

}
