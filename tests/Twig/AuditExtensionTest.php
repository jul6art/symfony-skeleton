<?php

namespace App\Tests\Twig;

use App\Twig\AuditExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

/**
 * Class AuditExtensionTest.
 */
class AuditExtensionTest extends TestCase
{
    /**
     * @var AuditExtension
     */
    private $auditExtension;

    /**
     * AuditExtensionTest constructor.
     *
     * @param null   $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->auditExtension = new AuditExtension();
    }

    /**
     * Test App\\Twig\\AuditExtension getFilters Method.
     */
    public function testGetFilters()
    {
        $filters = $this->auditExtension->getFilters();

        $this->assertEquals(1, \count($filters));

        $this->assertInstanceOf(TwigFilter::class, $filters[0]);
    }

    /**
     * Test App\\Twig\\AuditExtension blameAudit Method.
     *
     * ID is null
     */
    public function testBlameAudit()
    {
        $result = $this->auditExtension->blameAudit(null, []);

        $this->assertNull($result);
    }

    /**
     * Test App\\Twig\\AuditExtension blameAudit Method.
     *
     * Array is empty
     */
    public function testBlameAudit02()
    {
        $result = $this->auditExtension->blameAudit('1', []);

        $this->assertNull($result);
    }

    /**
     * Test App\\Twig\\AuditExtension blameAudit Method.
     *
     * Array does not contain key
     */
    public function testBlameAudit03()
    {
        $result = $this->auditExtension->blameAudit('1', []);

        $this->assertNull($result);
    }

    /**
     * Test App\\Twig\\AuditExtension blameAudit Method.
     *
     * Success
     */
    public function testBlameAudit04()
    {
        $result = $this->auditExtension->blameAudit('1', [1 => 'foo']);

        $this->assertEquals('foo', $result);
    }
}
