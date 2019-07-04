<?php

namespace App\Menu\Builder;

use App\Entity\User;
use App\Security\Voter\UserVoter;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class UserDropdownBuilder.
 */
class UserDropdownBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * NavbarBuilder constructor.
     *
     * @param FactoryInterface              $factory
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->factory = $factory;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createMenu(array $options)
    {
        $menu = $this->factory->createItem('root', [
            'childrenAttributes' => [
                'class' => 'dropdown-menu pull-right',
            ],
        ]);

        $this
            ->menuProfile($menu)
            ->menuChangePassword($menu)
            ->menuLogout($menu);

        return $menu;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return self
     */
    private function menuProfile(ItemInterface $menu): self
    {
        if ($this->authorizationChecker->isGranted(UserVoter::PROFILE, User::class)) {
            $menu->addChild('navbar.profile', [
                'route' => 'fos_user_profile_show',
            ])->setExtras([
                'icon' => 'person',
                'translation_domain' => 'navbar',
            ]);
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return self
     */
    private function menuChangePassword(ItemInterface $menu): self
    {
        if ($this->authorizationChecker->isGranted(UserVoter::CHANGE_PASSWORD, User::class)) {
            $menu->addChild('navbar.change_password', [
                'route' => 'fos_user_change_password',
            ])->setExtras([
                'icon' => 'lock',
                'translation_domain' => 'navbar',
            ]);

            $menu->addChild('navbar.divider', [
                'uri' => 'javascript:void(0);',
                'label' => false,
            ])->setAttribute('class', 'divider');
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return self
     */
    private function menuLogout(ItemInterface $menu): self
    {
        if ($this->authorizationChecker->isGranted(UserVoter::LOGOUT, User::class)) {
            $menu->addChild('navbar.logout', [
                'route' => 'fos_user_security_logout',
            ])->setExtras([
                'icon' => 'logout',
                'translation_domain' => 'navbar',
            ]);
        }

        return $this;
    }
}
