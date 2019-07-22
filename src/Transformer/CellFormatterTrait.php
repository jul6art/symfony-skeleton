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
 * Trait CellFormatterTrait.
 */
trait CellFormatterTrait
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
	 * @param string|null $text
	 * @param string $color
	 *
	 * @return string
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
    public function renderCellLabel(string $text = null, string $color = 'bg-blue-grey'): string
    {
		return $this->renderCell('includes/datatable/cell/label.html.twig', [
			'text' => $text,
			'color' => $color,
		]);
    }

	/**
	 * @param string|null $email
	 *
	 * @return string
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
    public function renderCellEmail(string $email = null): string
    {
		return $this->renderCell('includes/datatable/cell/email.html.twig', [
			'email' => $email,
		]);
    }

	/**
	 * @param string|null $text
	 * @param int $length
	 *
	 * @return string
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
    public function renderCellTruncate(string $text = null, int $length = 50): string
    {
		return $this->renderCell('includes/datatable/cell/truncate.html.twig', [
			'text' => $text,
			'length' => $length,
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
