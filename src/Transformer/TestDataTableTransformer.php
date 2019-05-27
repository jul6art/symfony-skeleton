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
 * Class TestDataTableTransformer
 * @package App\Transformer
 */
class TestDataTableTransformer extends TestTransformer implements NormalizerInterface
{
	use DataTableTransformerTrait;

	/**
	 * {@inheritdoc}
	 */
	public function normalize($test, $format = null, array $context = [])
	{
		if (!$test instanceof Test) {
			return [];
		}

		$output = parent::normalize($test, $format, $context);

//		$output['actions'] = $this->renderActions([]);
		$output['actions'] = $this->renderActions([], 'includes/datatable_actions_dropdown.html.twig');

		return $output;
	}

	/**
	 * {@inheritdoc}
	 */
	public function supportsNormalization($data, $format = null)
	{
		return $data instanceof Test;
	}
}