<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 27/08/2019
 * Time: 01:25.
 */

namespace App\Tests\Factory;

use App\Entity\Constants\GroupName;
use App\Entity\Constants\SettingValue;
use App\Entity\Constants\UserPassword;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Manager\GroupManager;
use Doctrine\ORM\NonUniqueResultException;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserFactoryTest.
 */
class UserFactoryTest extends WebTestCase
{
    /**
     * @var GroupManager
     */
    private $groupManager;

    /**
     * @var string
     */
    private $settingLocale;

    /**
     * @var string
     */
    private $settingTheme;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * UserFactoryTest constructor.
     *
     * @param string|null $name
     * @param array       $data
     * @param string      $dataName
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->faker = Factory::create();
        $client = static::createClient();
        $this->groupManager = $client->getContainer()->get('App\Manager\GroupManager');
        $this->settingLocale = $client->getContainer()->getParameter('locale');
        $this->settingTheme = SettingValue::SETTING_VALUE_DEFAULT_THEME;
    }

    /**
     * Test App\\Factory\\UserFactory build Method.
     *
     * User is not admin
     *
     */
    public function testCreate(): void
    {
        $group = GroupName::GROUP_NAME_USER;

        $user = UserFactory::create([
            'manager' => $this->groupManager,
            'group' => $group,
            'locale' => $this->settingLocale,
            'theme' => $this->settingTheme,
        ]);

        $user
            ->setFirstname($this->faker->firstName)
            ->setLastname($this->faker->lastName)
            ->setUsername($this->faker->userName)
            ->setEmail($this->faker->email)
            ->setGender($this->faker->randomElement(['m', 'f']))
            ->setPlainPassword(UserPassword::USER_PASSWORD_DEFAULT_VALUE);

        $this->assertInstanceOf(User::class, $user);

        $this->assertEquals(false, $user->isAdmin());
    }

    /**
     * Test App\\Factory\\UserFactory build Action.
     *
     * User is admin
     *
     */
    public function testCreate02(): void
    {
        $group = GroupName::GROUP_NAME_ADMIN;

        $user = UserFactory::create([
            'manager' => $this->groupManager,
            'group' => $group,
            'locale' => $this->settingLocale,
            'theme' => $this->settingTheme,
        ]);

        $user
            ->setFirstname($this->faker->firstName)
            ->setLastname($this->faker->lastName)
            ->setUsername($this->faker->userName)
            ->setEmail($this->faker->email)
            ->setGender($this->faker->randomElement(['m', 'f']))
            ->setPlainPassword(UserPassword::USER_PASSWORD_DEFAULT_VALUE);

        $this->assertInstanceOf(User::class, $user);

        $this->assertEquals(true, $user->isAdmin());
    }
}
