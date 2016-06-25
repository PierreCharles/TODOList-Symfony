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
    private $value;

    /**
     * @ORM\Column(type="integer")
     *
     * @ORM\ManyToOne(targetEntity="ToDoListBundle\Entity\TaskList")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="taskListId", referencedColumnName="id")
     * })
     */
    private $taskListId;

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
     * Set value
     *
     * @param string $value
     *
     * @return Task
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set taskListId
     *
     * @param integer $taskListId
     *
     * @return Task
     */
    public function setTaskListId($taskListId)
    {
        $this->taskListId = $taskListId;

        return $this;
    }

    /**
     * Get taskListId
     *
     * @return integer
     */
    public function getTaskListId()
    {
        return $this->taskListId;
    }
}
