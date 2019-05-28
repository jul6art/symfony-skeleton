<?php

namespace App\Menu;

use App\Manager\TestManagerTrait;
use Knp\Menu\FactoryInterface;

/**
 * Class NavbarBuilder
 * @package App\Menu
 */
class NavbarBuilder
{
	use TestManagerTrait;

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

		$randomTest = $this->testManager->findAll()[0];

		$menu->addChild('Latest Test', [
			'route' => 'admin_test_edit',
			'routeParameters' => ['id' => $randomTest->getId()]
		]);

		return $menu;
	}
}