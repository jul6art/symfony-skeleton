<?php

namespace App\Twig;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class LocaleExtension
 * @package App\Twig
 */
class LocaleExtension extends AbstractExtension
{
	/**
	 * @var TokenStorageInterface
	 */
	private $tokenStorage;

	/**
	 * @var string
	 */
	private $locale;

	/**
	 * ThemeExtension constructor.
	 *
	 * @param TokenStorageInterface $tokenStorage
	 */
	public function __construct(TokenStorageInterface $tokenStorage, string $locale)
	{
		$this->tokenStorage = $tokenStorage;
		$this->locale = $locale;
	}

	/**
	 * @return array
	 */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('locale', [$this, 'getLocale']),
            new TwigFunction('user_locale', [$this, 'getUserLocale']),
        ];
    }

	/**
	 * @return string
	 */
    public function getLocale(): string
    {
    	if (!is_null($locale = $this->getUserLocale())) {
    		return $locale;
	    }

        return $this->locale;
    }

	/**
	 * @return string
	 */
    public function getUserLocale(): ?string
    {
    	$user = $this->tokenStorage->getToken()->getUser();

        if ($user instanceof User && $user->hasSetting(User::SETTING_LOCALE)) {
        	return $user->getLocale();
        }

        return null;
    }
}
