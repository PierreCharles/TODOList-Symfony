<?php

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends \Symfony\Component\HttpKernel\Tests\Controller{

    /**
     * @Route("/", name="google_api_task_homepage
     * @param Request $request
     */
    public function indexAction(Request $request){

        if($request->query->has('code')){
            $googleClient =  $this->get("happyr.google.api.client");
            $accessToken = $googleClient->authentificate($request->query-get('code'));
            $googleClient->setAccessToken($accessToken);
        }
        return $this->render('SfGoogleApiBundle:Default:index.html.twig');
    }

    /**
     * @Route ("/home/", name="google_api_task_home")
     * @param Request $request
     */
    public function testAction(Request $request){
        return $this->render('SfGoogleApiBundle:Default:index.html.twig');
    }



}