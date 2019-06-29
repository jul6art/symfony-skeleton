<?php

namespace App\DataFixtures;

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
            Setting::SETTING_PROJECT_NAME => Setting::SETTING_PROJECT_NAME_VALUE,
            Setting::SETTING_BASE_TITLE => Setting::SETTING_BASE_TITLE_VALUE,
            Setting::SETTING_DEFAULT_THEME => Setting::SETTING_DEFAULT_THEME_VALUE,
            Setting::SETTING_AUDIT_LIMIT => Setting::SETTING_AUDIT_LIMIT_VALUE,
            Setting::SETTING_TOASTR_VERTICAL_POSITION => Setting::SETTING_TOASTR_VERTICAL_POSITION_VALUE,
            Setting::SETTING_TOASTR_HORIZONTAL_POSITION => Setting::SETTING_TOASTR_HORIZONTAL_POSITION_VALUE,
        ];

        foreach ($settings as $key => $value) {
            $setting = ($this->settingManager->create())
                ->setName($key)
                ->setValue($value);

            $this->addReference("setting_{$key}", $setting);
            $manager->persist($setting);
        }

        $manager->flush();
    }
}
