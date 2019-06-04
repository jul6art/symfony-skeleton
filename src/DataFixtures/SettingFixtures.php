<?php

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Setting;
use App\Manager\SettingManagerTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class SettingFixtures.
 */
class SettingFixtures extends Fixture
{
	use SettingManagerTrait;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $settings = [
            Setting::SETTING_PROJECT_NAME,
        ];

        foreach ($settings as $value) {
            $setting = ($this->settingManager->create())
	            ->setName($value);

            $this->addReference('setting_'.$value, $setting);
            $manager->persist($setting);
        }

        $manager->flush();
    }
}
