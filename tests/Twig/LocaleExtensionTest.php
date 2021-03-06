<?php

namespace App\Tests\Twig;

use App\Twig\LocaleExtension;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\TwigFunction;

/**
 * Class LocaleExtensionTest.
 */
class LocaleExtensionTest extends WebTestCase
{
    /**
     * @var MockObject
     */
    private $stack;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var LocaleExtension
     */
    private $localeExtension;

    /**
     * LocaleExtensionTest constructor.
     *
     * @param null   $name
     * @param array  $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $client = static::createClient();
        $this->stack = $this->createMock(RequestStack::class);
        $this->locale = $client->getContainer()->getParameter('locale');
        $this->localeExtension = new LocaleExtension(
            $this->stack,
            $this->locale
        );
    }

    /**
     * Test App\\Twig\\LocaleExtension getFunctions Method.
     */
    public function testGetFunctions(): void
    {
        $functions = $this->localeExtension->getFunctions();

        $this->assertEquals(4, \count($functions));

        $this->assertInstanceOf(TwigFunction::class, $functions[0]);

        $this->assertInstanceOf(TwigFunction::class, $functions[1]);

        $this->assertInstanceOf(TwigFunction::class, $functions[2]);

        $this->assertInstanceOf(TwigFunction::class, $functions[3]);
    }

    /**
     * Test App\\Twig\\LocaleExtension getLocale Method.
     *
     * Token has no user
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testGetLocale(): void
    {
        $result = $this->localeExtension->getLocale();

        $this->assertEquals(2, \strlen($result));

        $this->assertEquals($result, $this->locale);
    }

    /**
     * Test App\\Twig\\LocaleExtension getValidateLocale Method.
     *
     * Token has no user
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testGetValidateLocale(): void
    {
        $result = $this->localeExtension->getValidateLocale();

        $this->assertEquals(2, \strlen($result));

        $this->assertEquals($result, $this->locale);
    }

    /**
     * Test App\\Twig\\LocaleExtension getWysiwygLocale Method.
     *
     * Token has no user
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testGetWysiwygLocale(): void
    {
        $result = $this->localeExtension->getWysiwygLocale();

        $this->assertEquals(5, \strlen($result));

        $this->assertEquals($result, 'fr_FR');
    }

    /**
     * Test App\\Twig\\LocaleExtension getUserLocale Method.
     *
     * Token has no user
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function testUserLocale(): void
    {
        $result = $this->localeExtension->getUserLocale();

        $this->assertEquals(2, \strlen($result));

        $this->assertEquals($result, $this->locale);
    }
}
