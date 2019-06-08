<?php

namespace App\Twig;

use App\Entity\Functionality;
use App\Entity\Setting;
use App\Entity\User;
use App\Manager\FunctionalityManagerTrait;
use App\Manager\SettingManagerTrait;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class ThemeExtension.
 */
class ThemeExtension extends AbstractExtension
{
    use FunctionalityManagerTrait;
    use SettingManagerTrait;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * ThemeExtension constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
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
        $user = $this->tokenStorage->getToken()->getUser();
	    $default = $this->settingManager->findOneValueByName(Setting::SETTING_DEFAULT_THEME, Setting::SETTING_DEFAULT_THEME_VALUE);

	    if (!$this->functionalityManager->isActive(Functionality::FUNC_SWITCH_THEME)) {
	    	return $default;
        }

        if ($user instanceof User && $user->hasSetting(User::SETTING_THEME)) {
            return $user->getTheme();
        }

	    return $default;
    }
}
