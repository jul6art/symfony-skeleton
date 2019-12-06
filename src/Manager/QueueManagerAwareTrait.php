<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager;

/**
 * Trait QueueManagerAwareTrait.
 */
trait QueueManagerAwareTrait
{
    /**
     * @var QueueManager
     */
    protected $queueManager;

    /**
     * @return QueueManager
     */
    public function getQueueManager(): QueueManager
    {
        return $this->queueManager;
    }

    /**
     * @param QueueManager $queueManager
     *
     * @required
     */
    public function setQueueManager(QueueManager $queueManager): void
    {
        $this->queueManager = $queueManager;
    }
}
