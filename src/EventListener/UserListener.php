<?php

/**
 * Created by PhpStorm.
 * User: Jul6art
 * Date: 23/07/2019
 * Time: 13:35.
 */

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class UserListener.
 */
class UserListener
{
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
}
