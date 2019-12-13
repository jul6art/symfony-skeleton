<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Transformer;

use App\Entity\Test;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class TestTransformer.
 */
class TestTransformer implements NormalizerInterface
{
    use CellFormatterAwareTrait;

    /**
     * @param mixed $test
     * @param null  $format
     * @param array $contexts
     *
     * @return array|bool|float|int|string
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function normalize($test, $format = null, array $contexts = [])
    {
        if (!$test instanceof Test) {
            return [];
        }

        return [
            'id' => $test->getId(),
            'name' => $test->getName(),
            'content' => $this->renderCellTruncate($test->getContent()),
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
