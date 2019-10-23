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
     * Test App\\Twig\\AuditExtension getFilters Method.
     */
    public function testGetFilters()
    {
        $auditExtension = new AuditExtension();

        $filters = $auditExtension->getFilters();

        $this->assertEquals(1, \count($filters));

        $filter = $filters[0];

        $this->assertInstanceOf(TwigFilter::class, $filter);
    }

    /**
     * Test App\\Twig\\AuditExtension blameAudit Method.
     *
     * ID is null
     */
    public function testBlameAudit()
    {
        $auditExtension = new AuditExtension();

        $result = $auditExtension->blameAudit(null, []);

        $this->assertNull($result);
    }

    /**
     * Test App\\Twig\\AuditExtension blameAudit Method.
     *
     * Array is empty
     */
    public function testBlameAudit02()
    {
        $auditExtension = new AuditExtension();

        $result = $auditExtension->blameAudit('1', []);

        $this->assertNull($result);
    }

    /**
     * Test App\\Twig\\AuditExtension blameAudit Method.
     *
     * Array does not contain key
     */
    public function testBlameAudit03()
    {
        $auditExtension = new AuditExtension();

        $result = $auditExtension->blameAudit('1', []);

        $this->assertNull($result);
    }

    /**
     * Test App\\Twig\\AuditExtension blameAudit Method.
     *
     * Success
     */
    public function testBlameAudit04()
    {
        $auditExtension = new AuditExtension();

        $result = $auditExtension->blameAudit('1', [1 => 'foo']);

        $this->assertEquals('foo', $result);
    }
}
