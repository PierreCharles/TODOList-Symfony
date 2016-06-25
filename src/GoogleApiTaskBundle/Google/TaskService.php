<?php

namespace GoogleApiTaskBundle\Google;

use HappyR\Google\ApiBundle\Services\GoogleClient;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class TaskService to dialog with the Google API Client from happyr
 *
 * @package GoogleApiTaskBundle\Google
 */
class TaskService {
    
    private $service;

    /**
     * Constructor to initialize the Oauth connection with the token
     *
     * @param $client
     * @param $security
     */
    public function __construct($client, $security)
    {
        $token = json_decode($security->getToken()->getUser(), true);
        $googleClient = $client->getGoogleClient();

        /**
        // Refresh token
        if($googleClient->isAccessTokenExpired()) {
            // on récupère le refreshToken
            $refreshesToken = file_get_contents(__DIR__."/../tokenRefresh.txt");

            $googleClient->refreshToken($refreshesToken[$security->getToken()]);
            $tokens = $client->getAccessToken();
            $client->setAccessToken($tokens);
        }
         * */

        $this->service = new \Google_Service_Tasks($googleClient);
        $googleClient->setAccessToken($token);
    }

    /**
     * Return taskList from Google
     *
     * @return array TaskLists
     */
    public function getTaskLists()
    {
        return $this->service->tasklists->listTasklists()->getItems();
    }

    /**
     * Return the tasks from a taskList
     *
     * @param $taskList
     *
     * @return \Google_Service_Tasks_Tasks
     */
    public function getTasksFromList($taskList)
    {
       return $this->service->tasks->listTasks($taskList);
    }

    /**
     * Delete a taskList
     *
     * @param $taskList
     */
    public function deleteTasksList($taskList)
    {
      $this->service->tasklists->delete($taskList);
    }
}
