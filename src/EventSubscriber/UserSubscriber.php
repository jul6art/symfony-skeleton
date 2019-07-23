<?php

namespace App\EventSubscriber;

use App\Entity\Setting;
use App\Manager\SettingManagerTrait;
use App\Manager\UserManagerTrait;
use Doctrine\ORM\NonUniqueResultException;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class UserSubscriber.
 */
class UserSubscriber implements EventSubscriberInterface
{
    use SettingManagerTrait;
    use UserManagerTrait;

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
	        FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
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
        $this->userManager->updateGroups($user);
        $this->userManager->save($user);
    }
}
