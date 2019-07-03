<?php

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
        $this->test = $test;
        parent::__construct();
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
