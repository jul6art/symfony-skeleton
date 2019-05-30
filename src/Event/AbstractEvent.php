<?php

namespace App\Event;

use App\Entity\Test;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class AbstractEvent
 * @package App\Event
 */
abstract class AbstractEvent extends Event
{
	/**
	 * @var ArrayCollection
	 */
	protected $data;

	/**
	 * AbstractEvent constructor.
	 */
	public function __construct() {
		$this->data = new ArrayCollection();
	}

	/**
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function hasData(string $key)
	{
		return null !== $this->getOne($key);
	}

	/**
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function getOne(string $key)
	{
		if ($this->data->containsKey($key)) {
			return $this->data->get($key);
		}

		return null;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getData(): ArrayCollection
	{
		return $this->data;
	}

	/**
	 * @param ArrayCollection $data
	 *
	 * @return AbstractEvent
	 */
	public function setData( ArrayCollection $data ): AbstractEvent
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * @param string $key
	 * @param $value
	 *
	 * @return AbstractEvent
	 */
	public function addData(string $key, $value): AbstractEvent
	{
		$this->data->set($key, $value);

		return $this;
	}

	/**
	 * @param string $key
	 * @param $value
	 *
	 * @return AbstractEvent
	 */
	public function removeData(string $key, $value): AbstractEvent
	{
		if ($this->data->containsKey($key)) {
			$this->data->remove($key);
		}

		return $this;
	}
}