<?php

namespace App\Menu\Builder;

use App\Entity\Test;
use App\Entity\User;
use App\Manager\TestManagerTrait;
use App\Manager\UserManagerTrait;
use App\Security\Voter\DefaultVoter;
use App\Security\Voter\TestVoter;
use App\Security\Voter\UserVoter;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class NavbarBuilder.
 */
class NavbarBuilder
{
    use TestManagerTrait;
    use UserManagerTrait;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * NavbarBuilder constructor.
     *
     * @param FactoryInterface              $factory
     * @param RouterInterface               $router
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(FactoryInterface $factory, RouterInterface $router, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->factory = $factory;
        $this->router = $router;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createMenu(array $options)
    {
        $menu = $this->factory->createItem('root', [
            'childrenAttributes' => [
                'class' => 'list',
            ],
        ]);

        $menu->addChild('navbar.title')
             ->setAttribute('class', 'header')
             ->setExtra('translation_domain', 'navbar');

        $this
            ->menuImpersonate($menu)
            ->menuHome($menu)
            ->menuUsers($menu)
            ->menuTests($menu);

        return $menu;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return self
     */
    private function menuImpersonate(ItemInterface $menu): self
    {
        if ($this->authorizationChecker->isGranted('ROLE_PREVIOUS_ADMIN')) {
            $menu->addChild('navbar.impersonate', [
                'uri' => $this->router->generate('admin_user_list') . '?_switch_user=_exit',
            ])->setExtras([
                'icon' => 'undo',
                'translation_domain' => 'navbar',
            ])->setLinkAttribute('class', 'waves-effect waves-block visible-sm visible-xs');
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return self
     */
    private function menuHome(ItemInterface $menu): self
    {
        if ($this->authorizationChecker->isGranted(DefaultVoter::ACCESS_PAGE_HOME)) {
            $menu->addChild('navbar.home', [
                'route' => 'admin_homepage',
            ])->setExtras([
                'icon' => 'home',
                'translation_domain' => 'navbar',
            ])->setLinkAttribute('class', 'waves-effect waves-block');
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return NavbarBuilder
     *
     * @throws NonUniqueResultException
     */
    private function menuTests(ItemInterface $menu): self
    {
        $badge = false;

        if ($this->authorizationChecker->isGranted(TestVoter::LIST, Test::class)) {
            $count = $this->testManager->countAll();
            if ($count) {
                $badge = $count;
            }
        }

        if (
            $this->authorizationChecker->isGranted(TestVoter::LIST, Test::class)
            or $this->authorizationChecker->isGranted(TestVoter::ADD, Test::class)
        ) {
            $menu->addChild('navbar.test.title', [
                'uri' => 'javascript:void(0);',
            ])->setExtras([
                'icon' => 'print',
                'translation_domain' => 'navbar',
                'activated_routes' => ['admin_test_list', 'admin_test_edit', 'admin_test_view', 'admin_test_add'],
                'badge' => $badge,
            ])->setLinkAttribute('class', 'waves-effect waves-block menu-toggle');
        }

        if ($this->authorizationChecker->isGranted(TestVoter::LIST, Test::class)) {
            $menu['navbar.test.title']->addChild('navbar.test.list', [
                'route' => 'admin_test_list',
            ])->setExtras([
                'translation_domain' => 'navbar',
                'activated_routes' => ['admin_test_index', 'admin_test_edit', 'admin_test_view'],
                'badge' => $badge,
            ])->setLinkAttribute('class', 'waves-effect waves-block');
        }

        if ($this->authorizationChecker->isGranted(TestVoter::ADD, Test::class)) {
            $menu['navbar.test.title']->addChild('navbar.test.add', [
                'route' => 'admin_test_add',
            ])->setExtras([
                'translation_domain' => 'navbar',
            ])->setLinkAttribute('class', 'waves-effect waves-block');
        }

        return $this;
    }

    /**
     * @param ItemInterface $menu
     *
     * @return NavbarBuilder
     *
     * @throws NonUniqueResultException
     */
    private function menuUsers(ItemInterface $menu): self
    {
        $badge = false;

        if ($this->authorizationChecker->isGranted(UserVoter::LIST, User::class)) {
            $count = $this->userManager->countAll();
            if ($count) {
                $badge = $count;
            }
        }

        if (
            $this->authorizationChecker->isGranted(UserVoter::LIST, User::class)
            or $this->authorizationChecker->isGranted(UserVoter::ADD, User::class)
        ) {
            $menu->addChild('navbar.user.title', [
                'uri' => 'javascript:void(0);',
            ])->setExtras([
                'icon' => 'supervisor_account',
                'translation_domain' => 'navbar',
                'activated_routes' => ['admin_user_list', 'admin_user_edit', 'admin_user_view', 'admin_user_add'],
                'badge' => $badge,
            ])->setLinkAttribute('class', 'waves-effect waves-block menu-toggle');
        }

        if ($this->authorizationChecker->isGranted(UserVoter::LIST, User::class)) {
            $menu['navbar.user.title']->addChild('navbar.user.list', [
                'route' => 'admin_user_list',
            ])->setExtras([
                'translation_domain' => 'navbar',
                'activated_routes' => ['admin_user_index', 'admin_user_edit', 'admin_user_view'],
                'badge' => $badge,
            ])->setLinkAttribute('class', 'waves-effect waves-block');
        }

        if ($this->authorizationChecker->isGranted(UserVoter::ADD, User::class)) {
            $menu['navbar.user.title']->addChild('navbar.user.add', [
                'route' => 'admin_user_add',
            ])->setExtras([
                'translation_domain' => 'navbar',
            ])->setLinkAttribute('class', 'waves-effect waves-block');
        }

        return $this;
    }
}
