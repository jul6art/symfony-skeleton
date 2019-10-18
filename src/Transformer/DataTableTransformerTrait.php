<?php

/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/05/2019
 * Time: 14:35.
 */

namespace App\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Environment;

/**
 * Trait DataTableTransformerTrait.
 */
trait DataTableTransformerTrait
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var ArrayCollection
     */
    private $actions;

    /**
     * @return Environment
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }

    /**
     * @param Environment $twig
     *
     * @required
     */
    public function setTwig(Environment $twig): void
    {
        $this->twig = $twig;
    }

    /**
     * @return AuthorizationCheckerInterface
     */
    public function getAuthorizationChecker(): AuthorizationCheckerInterface
    {
        return $this->authorizationChecker;
    }

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     *
     * @required
     */
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker): void
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @return ArrayCollection
     */
    public function getActions(): ArrayCollection
    {
        return $this->actions;
    }

    /**
     * @required
     */
    public function setActions(): void
    {
        $this->actions = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function callbackItem()
    {
        $this->setActions();

        if (method_exists($this, 'callback')) {
            return $this->callback();
        }
    }

    /**
     * @param ArrayCollection $actions
     * @param string          $view
     *
     * @return string
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderActions(ArrayCollection $actions, string $view = 'includes/datatable/actions/list.html.twig'): string
    {
        $view = $this->twig->render($view, ['actions' => $actions]);

        $this->callbackItem();

        return $view;
    }

    /**
     * @param string      $name
     * @param string      $route
     * @param array       $routeParams
     * @param string|null $label
     * @param string|null $icon
     * @param string|null $color
     * @param array       $parameters
     *
     * @return self
     */
    public function addAction(string $name, string $route, array $routeParams = [], string $label = null, string $icon = null, string $color = null, array $parameters = []): self
    {
        if (!$this->actions->containsKey($name)) {
            $this->actions->set($name, [
                'label' => $label,
                'icon' => $icon,
                'color' => $color,
                'route' => $route,
                'routeParams' => $routeParams,
                'parameters' => $parameters,
            ]);
        }

        return $this;
    }

    /**
     * @return self
     */
    public function addForm(): self
    {
        //@TODO;

        return $this;
    }
}
