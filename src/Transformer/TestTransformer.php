<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:09
 */

namespace App\Transformer;

use App\Entity\Test;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class TestTransformer
 * @package App\Transformer
 */
class TestTransformer implements NormalizerInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function normalize($test, $format = null, array $context = [])
	{
		if (!$test instanceof Test) {
			return [];
		}

		return [
			'id' => $test->getId(),
			'name' => $test->getName(),
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function supportsNormalization($data, $format = null)
	{
		return $data instanceof Test;
	}
}