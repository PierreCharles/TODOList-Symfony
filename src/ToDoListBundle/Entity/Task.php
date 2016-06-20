<?php

namespace ToDoListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="ToDoListBundle\Repository\TaskRepository")
 */
class Task
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $statut;

    /**
     * @ORM\Column(type="integer")
     *
     * @ORM\ManyToOne(targetEntity="ToDoListBundle\Entity\Taskslist")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="taskListID", referencedColumnName="id")
     * })
     */
    private $taskListID;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Task
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Task
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set taskListID
     *
     * @param integer $taskListID
     *
     * @return Task
     */
    public function setTaskListID($taskListID)
    {
        $this->taskListID = $taskListID;

        return $this;
    }

    /**
     * Get taskListID
     *
     * @return integer
     */
    public function getTaskListID()
    {
        return $this->taskListID;
    }
}
