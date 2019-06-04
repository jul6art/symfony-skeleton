<?php

namespace App\EventSubscriber;

use App\Entity\Functionality;
use App\Entity\User;
use App\Manager\FunctionalityManagerTrait;
use App\Manager\UserManagerTrait;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class LocaleSubscriber.
 */
class LocaleSubscriber implements EventSubscriberInterface
{
    use UserManagerTrait;
    use FunctionalityManagerTrait;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @var string
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
        $this->available_locales = $available_locales;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 5]],
        ];
    }

	/**
	 * @param GetResponseEvent $event
	 *
	 * @throws NonUniqueResultException
	 */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$event->isMasterRequest()) {
            return;
        }

        $user = $this->tokenStorage->getToken()->getUser();

        $newLocale = $request->query->get('_locale', null);

        if (!in_array($newLocale, explode('|', $this->available_locales))) {
            $newLocale = null;
        }

        $userLocale = null;

        if ($user instanceof User
            && $user->hasSetting(User::SETTING_LOCALE)) {
            $userLocale = $user->getLocale();

            if (is_null($newLocale)) {
                $newLocale = $userLocale;
            }
        }

        if (!is_null($userLocale)) {
            if ($newLocale !== $userLocale) {
                $user->setLocale($newLocale);
                $this->userManager->save($user);
            }
        }

        if (!$this->functionalityManager->isActive(Functionality::FUNC_SWITCH_LOCALE)) {
        	$newLocale = $this->defaultLocale;
        }

        if (!is_null($newLocale)
            && $newLocale !== $request->getLocale()) {
            $request->getSession()->set('_locale', $newLocale);
            $request->setLocale($newLocale);
            $this->translator->setLocale($newLocale);
        }
    }
}
