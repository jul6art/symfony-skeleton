<?php

namespace App\Tests\Twig;

use App\Twig\EditInPlaceExtension;
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
     * Test App\\Twig\\EditInPlaceExtension getFunctions Method.
     */
    public function testGetFilters()
    {
        $authorizationChecker = $this->createMock(AuthorizationChecker::class);
        $requestStack = $this->createMock(RequestStack::class);
        $editInPlaceExtension = new EditInPlaceExtension($authorizationChecker, $requestStack);

        $functions = $editInPlaceExtension->getFunctions();

        $this->assertEquals(2, \count($functions));

        $function = $functions[0];

        $this->assertInstanceOf(TwigFunction::class, $function);
    }

    /**
     * Test App\\Twig\\EditInPlaceExtension edit Method.
     *
     * Token has no user
     */
    public function testEdit()
    {
        $authorizationChecker = $this->createMock(AuthorizationChecker::class);
        $requestStack = $this->createMock(RequestStack::class);
        $editInPlaceExtension = new EditInPlaceExtension($authorizationChecker, $requestStack);

        $result = $editInPlaceExtension->edit();

        $this->assertEmpty($result);
        $this->assertEquals(0, \strlen($result));
    }

    /**
     * Test App\\Twig\\EditInPlaceExtension translate Method.
     *
     * Token has no user
     */
    public function testTranslate()
    {
        $authorizationChecker = $this->createMock(AuthorizationChecker::class);
        $requestStack = $this->createMock(RequestStack::class);
        $editInPlaceExtension = new EditInPlaceExtension($authorizationChecker, $requestStack);

        $result = $editInPlaceExtension->translate();

        $this->assertEmpty($result);
        $this->assertEquals(0, \strlen($result));
    }
}
