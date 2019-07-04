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
 * Trait CellFormatTransformerTrait.
 */
trait CellFormatTransformerTrait
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @param Environment $twig
     *
     * @required
     */
    public function setTwigEnvironment(Environment $twig): void
    {
        $this->twig = $twig;
    }

	/**
	 * @param string $text
	 * @param string $color
	 *
	 * @return string
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
    public function renderCellLabel(string $text, string $color = 'bg-blue-grey'): string
    {
		return $this->renderCell('includes/datatable/cell/label.html.twig', [
			'text' => $text,
			'color' => $color,
		]);
    }

	/**
	 * @param string $email
	 *
	 * @return string
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
    public function renderCellEmail(string $email): string
    {
		return $this->renderCell('includes/datatable/cell/email.html.twig', [
			'email' => $email,
		]);
    }

	/**
	 * @param string $template
	 * @param array $parameters
	 *
	 * @return string
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
    public function renderCell(string $template, array $parameters): string
    {
    	return $this->twig->render($template, $parameters);
    }
}
