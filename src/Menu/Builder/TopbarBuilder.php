<?php

namespace App\Menu\Builder;

use App\Entity\Functionality;
use App\Security\Voter\FunctionalityVoter;
use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class TopbarBuilder.
 */
class TopbarBuilder
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
            'currentAsLink' => true,
        ]);

        if ($this->authorizationChecker->isGranted(FunctionalityVoter::CACHE_CLEAR, Functionality::class)) {
            $menu->addChild('topbar.cache_clear', [
                'route' => 'admin_cache',
                'label' => false,
            ])->setExtras([
                'icon' => 'sync',
                'translation_domain' => 'topbar',
            ]);
        }

        if ($this->authorizationChecker->isGranted(FunctionalityVoter::SWITCH_THEME, Functionality::class)
            or $this->authorizationChecker->isGranted(FunctionalityVoter::MANAGE_FUNCTIONALITIES, Functionality::class)
            or $this->authorizationChecker->isGranted(FunctionalityVoter::MANAGE_SETTINGS, Functionality::class)) {
            $menu->addChild('topbar.manage_functionalities', [
                'uri' => 'javascript:void(0);',
                'label' => false,
            ])->setExtras([
                'icon' => 'more_vert',
                'translation_domain' => 'topbar',
            ])->setAttribute('class', 'pull-right')
              ->setLinkAttribute('class', 'js-right-sidebar');
        }

        return $menu;
    }
}
