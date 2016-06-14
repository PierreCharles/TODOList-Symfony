<?php

namespace GoogleApiTaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use \stdClass;

class DefaultController extends Controller
{
    
    private $client;
     /**
     * @Route("/", name="google_api_task_homepage")
     */
    public function indexAction(Request $request)
    {
        if ($request->query->has('code')) {
            
            $googleClient = $this->get("happyr.google.api.client");
            $accessToken = $googleClient->authenticate($request->query->get('code'));
            $this->client=$googleClient->getGoogleClient();
        }
    }
    
    
     /**
     * @Route("/home/", name="google_api_task_home")
     */
    public function testAction(Request $request){
         $service = new \Google_Service_Tasks($this->client);

            // Print the first 10 task lists.
            $optParams = array(
              'maxResults' => 10,
            );
            $results = $service->tasklists->listTasklists($optParams);

            if (count($results->getItems()) == 0) {
              Return new Response("No task lists found.\n",200);
            } else {
                return new Response(json_encode($results->getItems),200);
            }
    }
}
