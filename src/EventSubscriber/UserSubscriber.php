<?php

namespace App\EventSubscriber;

use App\Entity\Setting;
use App\Event\UserEvent;
use App\Manager\SettingManagerTrait;
use App\Manager\UserManagerTrait;
use App\Traits\FlashBagTrait;
use App\Traits\TranslatorTrait;
use Doctrine\ORM\NonUniqueResultException;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Class UserSubscriber.
 */
class UserSubscriber implements EventSubscriberInterface
{
    use FlashBagTrait;
    use SettingManagerTrait;
    use TranslatorTrait;
    use UserManagerTrait;

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
            UserEvent::EDITED => 'onUserEdited',
        ];
    }

    /**
     * @param FormEvent $event
     *
     * @throws NonUniqueResultException
     */
    public function onRegistrationSuccess(FormEvent $event): void
    {
        $user = $event->getForm()->getData();
        $user->setLocale($event->getRequest()->getLocale());
        $user->setTheme($this->settingManager->findOneValueByName(Setting::SETTING_DEFAULT_THEME));
        $this->userManager
            ->updateGroups($user)
            ->save($user);
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $locale = $event->getRequest()->getSession()->get('_locale');

        if (null !== $locale and null !== $user and $user->getLocale() !== $locale) {
            $user->setLocale($locale);
            $this->userManager->save($user);
        }
    }

    /**
     * @param UserEvent $event
     */
    public function onUserEdited(UserEvent $event)
    {
        $this->flashBag->add('success', $this->translator->trans('notification.user.edited', [], 'notification'));
    }
}
