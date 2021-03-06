<?php

namespace App\Tests\Twig;

use App\Twig\JsonEncodeWithQuotesExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

/**
 * Class JsonEncodeWithQuotesExtensionTest.
 */
class JsonEncodeWithQuotesExtensionTest extends TestCase
{
    /**
     * @var JsonEncodeWithQuotesExtension
     */
    private $jsonEncodeWithQuotesExtension;

    /**
     * JsonEncodeWithQuotesExtensionTest constructor.
     *
     * @param null   $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->jsonEncodeWithQuotesExtension = new JsonEncodeWithQuotesExtension();
    }

    /**
     * Test App\\Twig\\JsonEncodeWithQuotesExtension getFilters Method.
     */
    public function testGetFilters(): void
    {
        $filters = $this->jsonEncodeWithQuotesExtension->getFilters();

        $this->assertEquals(1, \count($filters));

        $this->assertInstanceOf(TwigFilter::class, $filters[0]);
    }

    /**
     * Test App\\Twig\\JsonEncodeWithQuotesExtension blameAudit Method.
     *
     * Encode empty array
     */
    public function testJsonEncodeWithQuotes(): void
    {
        $array = [];

        $result = $this->jsonEncodeWithQuotesExtension->jsonEncodeWithQuotes($array);

        $this->assertEquals(2, \strlen($result));

        $this->assertEquals($array, json_decode($result));
    }

    /**
     * Test App\\Twig\\JsonEncodeWithQuotesExtension blameAudit Method.
     *
     * Encode not empty array
     */
    public function testJsonEncodeWithQuotes02(): void
    {
        $array = ['foo'];

        $result = $this->jsonEncodeWithQuotesExtension->jsonEncodeWithQuotes($array);

        $this->assertEquals(7, \strlen($result));

        $this->assertEquals($array, json_decode($result));
    }
}
