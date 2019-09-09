<?php

namespace App\Twig;

use App\Entity\Functionality;
use App\Entity\User;
use App\Manager\FunctionalityManagerTrait;
use App\Security\Voter\FunctionalityVoter;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class LocaleExtension.
 */
class LocaleExtension extends AbstractExtension
{
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
     * @var string
     */
    private $locale;

    /**
     * @var array
     */
    private $available_locales;

	/**
	 * LocaleExtension constructor.
	 *
	 * @param TokenStorageInterface $tokenStorage
	 * @param AuthorizationCheckerInterface $authorizationChecker
	 * @param RequestStack $stack
	 * @param string $locale
	 * @param array $available_locales
	 */
    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker, RequestStack $stack, string $locale, array $available_locales)
    {
        $this->tokenStorage = $tokenStorage;
	    $this->authorizationChecker = $authorizationChecker;
        $this->stack = $stack;
        $this->locale = $locale;
        $this->available_locales = $available_locales;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('locale', [$this, 'getLocale']),
            new TwigFunction('user_locale', [$this, 'getUserLocale']),
            new TwigFunction('validate_locale', [$this, 'getValidateLocale']),
            new TwigFunction('wysiwyg_locale', [$this, 'getWysiwygLocale']),
        ];
    }

    /**
     * @return string
     *
     * @throws NonUniqueResultException
     */
    public function getLocale(): string
    {
        return $this->getUserLocale();
    }

    /**
     * @return string
     *
     * @throws NonUniqueResultException
     */
    public function getValidateLocale(): string
    {
        $locale = $this->getUserLocale();

        if ($locale === 'pt') {
        	return 'pt_PT';
        }

        return $locale;
    }

    /**
     * @return string
     *
     * @throws NonUniqueResultException
     */
    public function getWysiwygLocale(): string
    {
        $locale = $this->getUserLocale();

        if ($locale === 'fr') {
        	return 'fr_FR';
        } elseif ($locale === 'en') {
        	return 'en_GB';
        } elseif ($locale === 'pt') {
        	return 'pt_PT';
        }

        return $locale;
    }

    /**
     * @return string|null
     *
     * @throws NonUniqueResultException
     */
    public function getUserLocale(): ?string
    {
        $request = $this->stack->getMasterRequest();

        if (!$request->request->has('user_locale') or !\in_array($request->request->get('user_locale'), $this->available_locales)) {
            $user = $this->tokenStorage->getToken()->getUser();
            $locale = $this->locale;

            if ($this->authorizationChecker->isGranted(FunctionalityVoter::SWITCH_LOCALE, Functionality::class)) {
                if ($user instanceof User and $user->hasSetting(User::SETTING_LOCALE)) {
                    $locale = $user->getLocale();
                }
            }

            $request->request->set('user_locale', $locale);

            return $locale;
        } else {
            return $request->request->get('user_locale');
        }
    }
}
