<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/05/2019
 * Time: 14:35.
 */

namespace App\Transformer;

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
     * @param array  $actions
     * @param string $view
     *
     * @return string
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderActions(array $actions = [], string $view = 'includes/datatable_actions_list.html.twig')
    {
        return $this->twig->render($view, ['actions' => $actions]);
    }
}
