<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class LocaleSubscriber
 * @package App\EventSubscriber
 */
class LocaleSubscriber implements EventSubscriberInterface
{
	/**
	 * @var string
	 */
	private $defaultLocale;

	/**
	 * LocaleListener constructor.
	 *
	 * @param string $locale
	 */
	public function __construct(string $locale)
	{
		$this->defaultLocale = $locale;
	}

	/**
	 * @param GetResponseEvent $event
	 */
	public function onKernelRequest(GetResponseEvent $event)
	{
		$request = $event->getRequest();
		if (!$request->hasPreviousSession()) {
			return;
		}

		if ($locale = $request->query->get('_locale')) {
			$request->getSession()->set('_locale', $locale);
			$request->setLocale($locale);
		} else {
			$request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
		}
	}

	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [
			KernelEvents::REQUEST => [['onKernelRequest', 10]],
		];
	}
}