<?php

namespace App\Tests\Twig;

use App\Twig\EditInPlaceExtension;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Twig\TwigFunction;

/**
 * Class EditInPlaceExtensionTest.
 */
class EditInPlaceExtensionTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $authorizationChecker;

    /**
     * @var MockObject
     */
    private $requestStack;

    /**
     * @var EditInPlaceExtension
     */
    private $editInPlaceExtension;

    /**
     * Test App\\Twig\\EditInPlaceExtension getFunctions Method.
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->authorizationChecker = $this->createMock(AuthorizationChecker::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->editInPlaceExtension = new EditInPlaceExtension($this->authorizationChecker, $this->requestStack);
    }

    /**
     * Test App\\Twig\\EditInPlaceExtension getFunctions Method.
     */
    public function testGetFunctions(): void
    {
        $functions = $this->editInPlaceExtension->getFunctions();

        $this->assertEquals(2, \count($functions));

        $this->assertInstanceOf(TwigFunction::class, $functions[0]);

        $this->assertInstanceOf(TwigFunction::class, $functions[1]);
    }

    /**
     * Test App\\Twig\\EditInPlaceExtension edit Method.
     *
     * Token has no user
     */
    public function testEdit(): void
    {
        $result = $this->editInPlaceExtension->edit();

        $this->assertEquals(0, \strlen($result));

        $this->assertEmpty($result);
    }

    /**
     * Test App\\Twig\\EditInPlaceExtension translate Method.
     *
     * Token has no user
     */
    public function testTranslate(): void
    {
        $result = $this->editInPlaceExtension->translate('key', ['key' => 'value'], 'domain');

        $this->assertEquals(0, \strlen($result));

        $this->assertEmpty($result);
    }
}
