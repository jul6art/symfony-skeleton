<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\EventListener;

use App\Entity\User;
use App\Event\UserEvent;
use App\Manager\UserManagerAwareTrait;
use App\Traits\FlashBagAwareTrait;
use App\Traits\TranslatorAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class UserListener.
 */
class UserListener
{
    use FlashBagAwareTrait;
    use TranslatorAwareTrait;
    use UserManagerAwareTrait;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * UserListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param RouterInterface       $router
     */
    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        $locale = $event->getRequest()->getSession()->get('_locale');

        if (null !== $locale and null !== $user and $user->getLocale() !== $locale) {
            $user->setLocale($locale);
            $this->userManager->save($user);
        }
    }

    /**
     * @param RequestEvent $event
     *
     * @throws NonUniqueResultException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$event->isMasterRequest()) {
            return;
        }

        $token = $this->tokenStorage->getToken();

        if (null !== $token and $token->getUser() instanceof User) {
            $route = $request->attributes->get('_route');

            if (
                \in_array($route, [
                    'fos_user_security_login',
                    'fos_user_registration_register',
                    'fos_user_resetting_request',
                ])
            ) {
                $event->setResponse(new RedirectResponse($this->router->generate('admin_homepage')));
            }
        }
    }

    /**
     * @param FormEvent $event
     *
     * @throws NonUniqueResultException
     */
    public function onRegistrationSuccess(FormEvent $event): void
    {
        $user = $event->getForm()->getData();
        $this->userManager
            ->presetSettings($user, $event->getRequest()->getLocale())
            ->presetGroups($user)
            ->save($user);
    }

    /**
     * @param UserEvent $event
     */
    public function onUserEdited(UserEvent $event): void
    {
        $this->flashBag->add('success', $this->translator->trans('notification.user.edited', [], 'notification'));
    }
}
