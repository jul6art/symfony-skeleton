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

	public function init(): void
	{
		$this->initActions();
	}

	public function initActions(): void
	{
		$this->actions = new ArrayCollection();
	}

	/**
	 * @param ArrayCollection $actions
	 * @param string $view
	 *
	 * @return string
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
    public function renderActions(ArrayCollection $actions, string $view = 'includes/datatable_actions_list.html.twig'): string
    {
        return $this->twig->render($view, ['actions' => $actions]);
    }

	/**
	 * @param string $name
	 * @param array $params
	 *
	 * @return self
	 */
    public function addAction(string $name, array $params = []): self
    {
    	if (!$this->actions->containsKey($name)) {
    		$this->actions->set($name, $params);
	    }

	    return $this;
    }
}
