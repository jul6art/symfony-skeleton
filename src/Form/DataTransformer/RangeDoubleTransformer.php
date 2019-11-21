<?php

/**
 * Created by PhpStorm.
 * User: Jul6art
 * Date: 22/06/2019
 * Time: 12:26.
 */

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class RangeDoubleTransformer.
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
        if (null === $data or !\is_iterable($data) or empty($data)) {
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
        if ('' === $string or null === $string) {
            return null;
        }

        $data = explode(',', $string);

        return array_map(function ($item) {
            return (float) $item;
        }, $data);
    }
}
