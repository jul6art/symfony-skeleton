<?php

namespace App\Twig;

use App\Entity\Functionality;
use App\Security\Voter\FunctionalityVoter;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class EditInPlaceExtension.
 */
class EditInPlaceExtension extends AbstractExtension
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * EditInPlaceExtension constructor.
     *
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param RequestStack                  $stack
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker, RequestStack $stack)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->stack = $stack;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('edit_in_place', [$this, 'edit']),
            new TwigFunction('translate_in_place', [$this, 'translate']),
        ];
    }

    /**
     * @return string
     */
    public function edit(): string
    {
        $request = $this->stack->getMasterRequest();

        if (null === $request) {
            return '';
        }

        if (!$request->request->has('edit_in_place')) {
            $attributes = '';

            if ($this->authorizationChecker->isGranted(FunctionalityVoter::EDIT_IN_PLACE, Functionality::class)) {
                $attributes = ' data-provide=wysiwyg data-inline data-edit';
            }

            $request->request->set('edit_in_place', $attributes);

            return $attributes;
        }

        return $request->request->get('edit_in_place');
    }

    /**
     * @param string $key
     * @param string $domain
     *
     * @return string
     */
    public function translate(string $key, string $domain): string
    {
        $request = $this->stack->getMasterRequest();

        if (null === $request) {
            return '';
        }

        if (!$request->request->has('translate_in_place')) {
            $attributes = '';

            if (
                $this->authorizationChecker->isGranted(FunctionalityVoter::EDIT_IN_PLACE, Functionality::class)
                and $this->authorizationChecker->isGranted(FunctionalityVoter::SWITCH_LOCALE, Functionality::class)
            ) {
                $attributes = " data-provide=wysiwyg data-inline data-translate data-domain=$domain data-key=$key";
            }

            $request->request->set('translate_in_place', $attributes);

            return $attributes;
        }

        return $request->request->get('translate_in_place');
    }
}
