<?php

namespace App\Twig;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class ThemeExtension
 * @package App\Twig
 */
class ThemeExtension extends AbstractExtension
{
	/**
	 * @var TokenStorageInterface
	 */
	private $tokenStorage;

	/**
	 * @var string
	 */
	private $default_theme;

	/**
	 * ThemeExtension constructor.
	 *
	 * @param TokenStorageInterface $tokenStorage
	 */
	public function __construct(TokenStorageInterface $tokenStorage, string $default_theme)
	{
		$this->tokenStorage = $tokenStorage;
		$this->default_theme = $default_theme;
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
	 */
    public function getThemeName()
    {
    	$user = $this->tokenStorage->getToken()->getUser();

        if ($user instanceof User) {
        	return $user->getTheme();
        }

        return $this->default_theme;
    }
}
