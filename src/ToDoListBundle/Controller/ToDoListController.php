<?php

namespace ToDoListBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Principal class to acces to the home of the website.
 */
class ToDoListController extends Controller
{
    /**
     * Method to render the main tasks list page.
     *
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('ToDoListBundle:TaskList');
        $tasksList = $repository->findAll();
        return $this->render('ToDoListBundle:TaskViews:index.html.twig', array('tasksLists' => $tasksList));
    }

}
