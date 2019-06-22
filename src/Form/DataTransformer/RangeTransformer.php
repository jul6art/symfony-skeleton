<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 22/06/2019
 * Time: 12:26
 */

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class RangeTransformer
 * @package App\Form\DataTransformer
 */
class RangeTransformer implements DataTransformerInterface
{
	/**
	 * @param mixed $data
	 *
	 * @return string
	 */
	public function transform($data): string
	{
		if (null === $data || !is_numeric($data)) {
			return '';
		}

		return (string) $data;
	}

	/**
	 * @param mixed $string
	 *
	 * @return float|null
	 */
	public function reverseTransform($string): ?float
	{
		return $string === '' || $string === null ? null : (float) $string;
	}
}