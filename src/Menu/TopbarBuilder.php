<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;

/**
 * Class TopbarBuilder
 * @package App\Menu
 */
class TopbarBuilder
{
	/**
	 * @var FactoryInterface
	 */
	private $factory;

	/**
	 * NavbarBuilder constructor.
	 *
	 * @param FactoryInterface $factory
	 */
	public function __construct(FactoryInterface $factory)
	{
		$this->factory = $factory;
	}

	public function createMenu(array $options)
	{
		$menu = $this->factory->createItem('root');

		$menu->addChild('Home', ['route' => 'admin_homepage']);

		return $menu;
	}
}