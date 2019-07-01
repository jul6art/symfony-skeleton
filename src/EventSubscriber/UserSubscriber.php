<?php

namespace App\EventSubscriber;

use App\Entity\Functionality;
use App\Entity\Setting;
use App\Event\UserEvent;
use App\Manager\FunctionalityManagerTrait;
use App\Manager\SettingManagerTrait;
use App\Manager\UserManagerTrait;
use Doctrine\ORM\NonUniqueResultException;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserSubscriber.
 */
class UserSubscriber extends AbstractSubscriber implements EventSubscriberInterface
{
    use FunctionalityManagerTrait;
    use SettingManagerTrait;
    use UserManagerTrait;

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
	        FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
            UserEvent::ADDED => 'onUserAdded',
            UserEvent::EDITED => 'onUserEdited',
            UserEvent::DELETED => 'onUserDeleted',
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
        $user->setLocale($event->getRequest()->getSession()->get('_locale'));
        $user->setTheme($this->settingManager->findOneValueByName(Setting::SETTING_DEFAULT_THEME));
        $this->userManager->save($user);
    }

    /**
     * @param UserEvent $event
     */
    public function onUserAdded(UserEvent $event): void
    {
        $this->flashBag->add('success', $this->translator->trans('notification.user.added', [], 'notification'));
    }

    /**
     * @param Event $event
     */
    public function onUserEdited(UserEvent $event): void
    {
        $this->flashBag->add('success', $this->translator->trans('notification.user.edited', [], 'notification'));
    }

    /**
     * @param UserEvent $event
     *
     * @throws NonUniqueResultException
     */
    public function onUserDeleted(UserEvent $event): void
    {
        //
        // notifications for deletion are currently made by sweetalert dialog if func is active
        //
        if (!$this->functionalityManager->isActive(Functionality::FUNC_CONFIRM_DELETE)) {
            $this->flashBag->add('success', $this->translator->trans('notification.user.deleted', [], 'notification'));
        }
    }
}
