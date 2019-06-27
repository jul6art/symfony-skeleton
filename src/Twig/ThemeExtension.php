<?php

namespace App\Twig;

use App\Entity\Functionality;
use App\Entity\Setting;
use App\Entity\User;
use App\Manager\FunctionalityManagerTrait;
use App\Manager\SettingManagerTrait;
use App\Security\Voter\FunctionalityVoter;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class ThemeExtension.
 */
class ThemeExtension extends AbstractExtension
{
    use SettingManagerTrait;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

	/**
	 * @var AuthorizationCheckerInterface
	 */
	private $authorizationChecker;

    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @var array
     */
    private $available_colors;

	/**
	 * ThemeExtension constructor.
	 *
	 * @param TokenStorageInterface $tokenStorage
	 * @param AuthorizationCheckerInterface $authorizationChecker
	 * @param RequestStack $stack
	 * @param array $available_colors
	 */
    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker, RequestStack $stack, array $available_colors)
    {
        $this->tokenStorage = $tokenStorage;
	    $this->authorizationChecker = $authorizationChecker;
        $this->stack = $stack;
        $this->available_colors = $available_colors;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('theme_name', [$this, 'getThemeName']),
        ];
    }

    /**
     * @return string
     *
     * @throws NonUniqueResultException
     */
    public function getThemeName(): string
    {
        $request = $this->stack->getMasterRequest();

        if (!$request->request->has('theme_name') || !\in_array($request->request->get('theme_name'), $this->available_colors)) {
            $user = $this->tokenStorage->getToken()->getUser();
            $theme = $this->settingManager->findOneValueByName(Setting::SETTING_DEFAULT_THEME, Setting::SETTING_DEFAULT_THEME_VALUE);

            if ($this->authorizationChecker->isGranted(FunctionalityVoter::SWITCH_THEME, Functionality::class)) {
                if ($user instanceof User && $user->hasSetting(User::SETTING_THEME)) {
                    $theme = $user->getTheme();
                }
            }

            $request->request->set('theme_name', $theme);

            return $theme;
        } else {
            return $request->request->get('theme_name');
        }
    }
}
