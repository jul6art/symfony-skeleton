<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

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
    const SESSION_KEY = 'edit_in_place';

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

        if (!$request->request->has(self::SESSION_KEY)) {
            $attributes = '';

            if ($this->authorizationChecker->isGranted(FunctionalityVoter::EDIT_IN_PLACE, Functionality::class)) {
                $attributes = ' data-provide=wysiwyg data-inline data-edit';
            }

            $request->request->set(self::SESSION_KEY, $attributes);

            return $attributes;
        }

        return $request->request->get(self::SESSION_KEY);
    }

    /**
     * @param string       $key
     * @param array|string $parameters
     * @param string       $domain
     *
     * @return string
     */
    public function translate(string $key, array $parameters, string $domain): string
    {
        $request = $this->stack->getMasterRequest();
        $parametersJSON = json_encode($parameters, JSON_UNESCAPED_UNICODE);
        $parametersJSON = str_replace(' ', '&nbsp;', $parametersJSON);
        $requestKey = sprintf('translate_in_place_%s_%s_%s', $domain, $key, $parametersJSON);

        if (null === $request) {
            return '';
        }

        if (!$request->request->has($requestKey)) {
            $attributes = '';

            if ($this->authorizationChecker->isGranted(FunctionalityVoter::EDIT_IN_PLACE, Functionality::class)) {
                $attributes = " data-provide=wysiwyg data-inline data-translate data-domain=$domain data-key=$key data-parameters=$parametersJSON";
            }

            $request->request->set($requestKey, $attributes);

            return $attributes;
        }

        return $request->request->get($requestKey);
    }
}
