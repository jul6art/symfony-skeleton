<?php

namespace App\Event;

use App\Entity\Test;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class TestEvent
 * @package App\Event
 */
class TestEvent extends AbstractEvent
{
	const ADDED = 'event.test.added';
	const EDITED = 'event.test.edited';
	const DELETED = 'event.test.deleted';
	/**
	 * @var Test
	 */
	private $test;

	/**
	 * TestEvent constructor.
	 *
	 * @param Test $test
	 */
	public function __construct(Test $test) {
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
	public function setTest( Test $test ): self
	{
		$this->test = $test;

		return $this;
	}
}
