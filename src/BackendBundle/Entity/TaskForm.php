<?php

namespace BackendBundle\Entity;

/**
 * TaskForm
 */
class TaskForm extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $taskFormId;

    /**
     * @var string
     */
    private $taskId;


    /**
     * Get taskFormId
     *
     * @return integer
     */
    public function getTaskFormId()
    {
        return $this->taskFormId;
    }

    /**
     * Set taskId
     *
     * @param string $taskId
     *
     * @return TaskForm
     */
    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;

        return $this;
    }

    /**
     * Get taskId
     *
     * @return string
     */
    public function getTaskId()
    {
        return $this->taskId;
    }



    public function toArray() {
        return get_object_vars($this);
    }
}

