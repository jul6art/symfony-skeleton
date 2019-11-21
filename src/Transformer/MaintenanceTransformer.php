<?php

/**
 * Created by PhpStorm.
 * User: Jul6art
 * Date: 23/05/2019
 * Time: 09:09.
 */

namespace App\Transformer;

use App\Entity\Maintenance;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class MaintenanceTransformer.
 */
class MaintenanceTransformer implements NormalizerInterface
{
    use CellFormatterTrait;

    /**
     * @param mixed $maintenance
     * @param null  $format
     * @param array $contexts
     *
     * @return array|bool|float|int|string
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function normalize($maintenance, $format = null, array $contexts = [])
    {
        if (!$maintenance instanceof Maintenance) {
            return [];
        }

        return [
            'id' => $maintenance->getId(),
            'active' => $this->renderCell('maintenance/cell/active.html.twig', ['active' => $maintenance->isActive()]),
            'exceptionIpList' => $this->renderCell('includes/datatable/cell/list.html.twig', ['list' => $maintenance->getExceptionIpList()]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Maintenance;
    }
}
