<?php

namespace App\Twig;

use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class LocaleExtension.
 */
class LocaleExtension extends AbstractExtension
{
    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @var string
     */
    private $locale;

    /**
     * LocaleExtension constructor.
     *
     * @param RequestStack $stack
     * @param string       $locale
     */
    public function __construct(RequestStack $stack, string $locale)
    {
        $this->stack = $stack;
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

        if ('pt' === $locale) {
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

        if ('fr' === $locale) {
            return 'fr_FR';
        }

        if ('en' === $locale) {
            return 'en_GB';
        }

        if ('pt' === $locale) {
            return 'pt_PT';
        }

        return $locale;
    }

    /**
     * @return string
     *
     * @throws NonUniqueResultException
     */
    public function getUserLocale(): string
    {
        $request = $this->stack->getMasterRequest();

        if (null === $request) {
            return $this->locale;
        }

        return $request->getLocale();
    }
}
