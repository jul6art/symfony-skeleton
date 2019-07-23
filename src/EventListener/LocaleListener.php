<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/07/2019
 * Time: 13:35
 */

namespace App\EventListener;

use App\Entity\Functionality;
use App\Entity\User;
use App\Manager\FunctionalityManagerTrait;
use App\Manager\UserManagerTrait;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class LocaleListener
 * @package App\EventListener
 */
class LocaleListener {
	use UserManagerTrait;
	use FunctionalityManagerTrait;

	/**
	 * @var string
	 */
	private $defaultLocale;

	/**
	 * @var array
	 */
	private $available_locales;

	/**
	 * @var TokenStorageInterface
	 */
	private $tokenStorage;

	/**
	 * @var TranslatorInterface
	 */
	private $translator;

	/**
	 * LocaleSubscriber constructor.
	 *
	 * @param string                $locale
	 * @param string                $available_locales
	 * @param TokenStorageInterface $tokenStorage
	 * @param TranslatorInterface   $translator
	 */
	public function __construct(string $locale, string $available_locales, TokenStorageInterface $tokenStorage, TranslatorInterface $translator)
	{
		$this->defaultLocale = $locale;
		$this->available_locales = explode('|', $available_locales);
		$this->tokenStorage = $tokenStorage;
		$this->translator = $translator;
	}

	/**
	 * @param GetResponseEvent $event
	 *
	 * @throws NonUniqueResultException
	 */
	public function onKernelRequest(GetResponseEvent $event): void
	{
		$request = $event->getRequest();
		if (!$event->isMasterRequest()) {
			return;
		}

		$user = $this->tokenStorage->getToken()->getUser();

		$newLocale = $request->query->get('_locale');

		if (!\in_array($newLocale, $this->available_locales)) {
			$newLocale = null;
		}

		$userLocale = null;

		if ($user instanceof User
		    && $user->hasSetting(User::SETTING_LOCALE)) {
			$userLocale = $user->getLocale();

			if (null === $newLocale) {
				$newLocale = $userLocale;
			}
		}

		if (null !== $userLocale) {
			if ($newLocale !== $userLocale) {
				$user->setLocale($newLocale);
				$this->userManager->save($user);
			}
		}

		if (!$this->functionalityManager->isActive(Functionality::FUNC_SWITCH_LOCALE)) {
			$newLocale = $this->defaultLocale;
		}

		if (null !== $newLocale
		    && $newLocale !== $request->getLocale()) {
			$request->getSession()->set('_locale', $newLocale);
			$request->setLocale($newLocale);
			$this->translator->setLocale($newLocale);
		}

		$sessionLocale = $request->getSession()->get('_locale');
		if (null !== $sessionLocale
		    && $request->getLocale() !== $sessionLocale) {
			$request->setLocale($request->getSession()->get('_locale'));
		}
	}
}