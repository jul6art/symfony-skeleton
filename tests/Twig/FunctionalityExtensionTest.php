<?php

namespace App\Tests\Twig;

use App\Manager\FunctionalityManager;
use App\Twig\FunctionalityExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

/**
 * Class FunctionalityExtensionTest.
 */
class FunctionalityExtensionTest extends TestCase
{
    /**
     * @var FunctionalityExtension
     */
    private $functionalityExtension;

    /**
     * FunctionalityExtensionTest constructor.
     *
     * @param null   $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->functionalityExtension = new FunctionalityExtension();
        $this->functionalityExtension->setFunctionalityManager($this->createMock(FunctionalityManager::class));
    }

    /**
     * Test App\\Twig\\FunctionalityExtension getFilters Method.
     */
    public function testGetFunctions(): void
    {
        $functions = $this->functionalityExtension->getFunctions();

        $this->assertEquals(2, \count($functions));

        $this->assertInstanceOf(TwigFunction::class, $functions[0]);

        $this->assertInstanceOf(TwigFunction::class, $functions[1]);
    }
}
