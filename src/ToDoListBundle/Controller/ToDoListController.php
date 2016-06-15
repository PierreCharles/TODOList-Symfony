<?php

namespace ToDoListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Principal class to acces to the home of the website.
 */
class ToDoListController extends Controller
{
    /**
     * Method to render the main todo list page.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('ToDoListBundle::index.html.twig');
    }
}
