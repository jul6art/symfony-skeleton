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
 * Class RangeDoubleTransformer
 * @package App\Form\DataTransformer
 */
class RangeDoubleTransformer implements DataTransformerInterface
{
	/**
	 * @param mixed $data
	 *
	 * @return string
	 */
	public function transform($data): string
	{
		if (null === $data || !is_iterable($data) || empty($data)) {
			return '';
		}

		return implode(', ', $data);
	}

	/**
	 * @param mixed $string
	 *
	 * @return array|null
	 */
	public function reverseTransform($string): ?array
	{
		if ($string === '' || $string === null) {
			return null;
		}

		$data = explode(',', $string);
		return array_map(function ($item) {
			return (float) $item;
		}, $data);
	}
}