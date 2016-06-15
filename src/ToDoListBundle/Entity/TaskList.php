<?php

namespace ToDoListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskList.
 *
 * @ORM\Table(name="task_list")
 * @ORM\Entity(repositoryClass="ToDoListBundle\Repository\TaskListRepository")
 */
class TaskList
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\Column(name="taskList", type="array")
     */
    private $taskList;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="addDate", type="datetime")
     */
    private $addDate;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return TaskList
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return TaskList
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set taskList.
     *
     * @param array $taskList
     *
     * @return TaskList
     */
    public function setTaskList($taskList)
    {
        $this->taskList = $taskList;

        return $this;
    }

    /**
     * Get taskList.
     *
     * @return array
     */
    public function getTaskList()
    {
        return $this->taskList;
    }

    /**
     * Set addDate.
     *
     * @param \DateTime $addDate
     *
     * @return TaskList
     */
    public function setAddDate($addDate)
    {
        $this->addDate = $addDate;

        return $this;
    }

    /**
     * Get addDate.
     *
     * @return \DateTime
     */
    public function getAddDate()
    {
        return $this->addDate;
    }
}
