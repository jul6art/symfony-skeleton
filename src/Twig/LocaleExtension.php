<?php

namespace App\Twig;

use App\Entity\Functionality;
use App\Entity\User;
use App\Manager\FunctionalityManagerTrait;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class LocaleExtension.
 */
class LocaleExtension extends AbstractExtension
{
    use FunctionalityManagerTrait;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

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
     * @param RequestStack          $stack
     * @param string                $locale
     */
    public function __construct(TokenStorageInterface $tokenStorage, RequestStack $stack, string $locale, string $available_locales)
    {
        $this->tokenStorage = $tokenStorage;
        $this->stack = $stack;
        $this->locale = $locale;
        $this->available_locales = explode('|', $available_locales);
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
     *
     * @throws NonUniqueResultException
     */
    public function getLocale(): string
    {
        return $this->getUserLocale();
    }

    /**
     * @return string|null
     *
     * @throws NonUniqueResultException
     */
    public function getUserLocale(): ?string
    {
        $request = $this->stack->getMasterRequest();

        if (!$request->request->has('user_locale') || !in_array($request->request->get('user_locale'), $this->available_locales)) {
            $user = $this->tokenStorage->getToken()->getUser();
            $locale = $this->locale;

            if ($this->functionalityManager->isActive(Functionality::FUNC_SWITCH_LOCALE)) {
                if ($user instanceof User && $user->hasSetting(User::SETTING_LOCALE)) {
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
