<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AbstractSubscriber
 * @package App\EventSubscriber
 */
abstract class AbstractSubscriber
{
	/**
	 * @var FlashBagInterface
	 */
	protected $flashBag;

	/**
	 * @var TranslatorInterface
	 */
	protected $translator;

	/**
	 * AbstractSubscriber constructor.
	 *
	 * @param FlashBagInterface $flashBag
	 */
	public function __construct(FlashBagInterface $flashBag, TranslatorInterface $translator)
	{
		$this->flashBag = $flashBag;
		$this->translator = $translator;
	}
}