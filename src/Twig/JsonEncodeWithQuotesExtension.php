<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class JsonEncodeWithQuotesExtension.
 */
class JsonEncodeWithQuotesExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('json_encode_with_quotes', [$this, 'jsonEncodeWithQuotes', ['is_safe' => 'html']]),
        ];
    }

    /**
     * @param array $array
     *
     * @return string
     */
    public function jsonEncodeWithQuotes(array $array): string
    {
        return (string) json_encode($array, JSON_UNESCAPED_UNICODE);
    }
}
