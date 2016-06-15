<?php

namespace GoogleApiTaskBundle\Google;

use HappyR\Google\ApiBundle\Services\GoogleClient;
use Symfony\Component\Security\Core\SecurityContext;

class TaskService {
    
    private $service;
    
    public function __construct($client, $security)
    {
        $token = json_decode($security->getToken()->getUser(), true);
        $googleClient = $client->getGoogleClient();
        $googleClient->setAccessToken($token);
        $this->service = new \Google_Service_Tasks($googleClient);
    }
    
    public function getTaskLists()
    {
        return $this->service->tasklists->listTasklists()->getItems();
    }
    
   public function getTasksFromList($taskList)
   {
       return $this->service->tasks->listTasks($taskList);
   }
   
   public function deleteTasksList($taskList)
   {
      $this->service->tasklists->delete($taskList);
   }
}
