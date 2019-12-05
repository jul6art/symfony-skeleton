<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Event;

use App\Entity\Test;

/**
 * Class TestEvent.
 */
class TestEvent extends AbstractEvent
{
    public const ADDED = 'event.test.added';
    public const EDITED = 'event.test.edited';
    public const DELETED = 'event.test.deleted';
    /**
     * @var Test
     */
    private $test;

    /**
     * TestEvent constructor.
     *
     * @param Test $test
     */
    public function __construct(Test $test)
    {
        parent::__construct();
        $this->test = $test;
    }

    /**
     * @return Test
     */
    public function getTest(): Test
    {
        return $this->test;
    }

    /**
     * @param Test $test
     *
     * @return self
     */
    public function setTest(Test $test): self
    {
        $this->test = $test;

        return $this;
    }
}
