<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\EventListener;

use App\Entity\Constants\FunctionalityName;
use App\Entity\Constants\UserSetting;
use App\Entity\User;
use App\Manager\Traits\FunctionalityManagerAwareTrait;
use App\Manager\Traits\UserManagerAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class LocaleListener.
 */
class LocaleListener
{
    use UserManagerAwareTrait;
    use FunctionalityManagerAwareTrait;

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
     * @param array                 $available_locales
     * @param TokenStorageInterface $tokenStorage
     * @param TranslatorInterface   $translator
     */
    public function __construct(string $locale, array $available_locales, TokenStorageInterface $tokenStorage, TranslatorInterface $translator)
    {
        $this->defaultLocale = $locale;
        $this->available_locales = $available_locales;
        $this->tokenStorage = $tokenStorage;
        $this->translator = $translator;
    }

    /**
     * @param KernelEvent $event
     *
     * @throws NonUniqueResultException
     */
    public function onKernelRequest(KernelEvent $event): void
    {
        $request = $event->getRequest();
        if (!$event->isMasterRequest()) {
            return;
        }

        $user = null !== $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser() : null;

        $newLocale = $request->query->get('_locale');

        if (!\in_array($newLocale, $this->available_locales)) {
            $newLocale = null;
        }

        if (!$this->functionalityManager->isActive(FunctionalityName::FUNC_NAME_SWITCH_LOCALE)) {
            $newLocale = $this->defaultLocale;
        } else {
            $userLocale = null;

            if (
                $user instanceof User
                and $user->hasSetting(UserSetting::USER_SETTING_LOCALE)
            ) {
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
        }

        $sessionLocale = $request->getSession()->get('_locale');
        if (
            null !== $sessionLocale
            and $newLocale !== $sessionLocale
        ) {
            $newLocale = $sessionLocale;
        }

        if (
            null !== $newLocale
            and $newLocale !== $request->getLocale()
        ) {
            $request->getSession()->set('_locale', $newLocale);
            $request->setLocale($newLocale);
            $this->translator->setLocale($newLocale);
        }
    }
}
