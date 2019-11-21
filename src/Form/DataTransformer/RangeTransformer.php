<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class RangeTransformer.
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
        if (null === $data or !\is_numeric($data)) {
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
        if ('' === $string or null === $string) {
            return null;
        }

        return (float) $string;
    }
}
