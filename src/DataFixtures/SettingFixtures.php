<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\DataFixtures;

use App\Entity\Constants\SettingName;
use App\Manager\Traits\SettingManagerAwareTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class SettingFixtures.
 */
class SettingFixtures extends Fixture
{
    use SettingManagerAwareTrait;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $settings = [
            SettingName::SETTING_NAME_PROJECT_NAME => SettingName::SETTING_VALUE_PROJECT_NAME,
            SettingName::SETTING_NAME_BASE_TITLE => SettingName::SETTING_VALUE_BASE_TITLE,
            SettingName::SETTING_NAME_DEFAULT_THEME => SettingName::SETTING_VALUE_DEFAULT_THEME,
            SettingName::SETTING_NAME_AUDIT_LIMIT => SettingName::SETTING_VALUE_AUDIT_LIMIT,
            SettingName::SETTING_NAME_TOASTR_VERTICAL_POSITION => SettingName::SETTING_VALUE_TOASTR_VERTICAL_POSITION,
            SettingName::SETTING_NAME_TOASTR_HORIZONTAL_POSITION => SettingName::SETTING_VALUE_TOASTR_HORIZONTAL_POSITION,
        ];

        array_walk($settings, function (string $value, string $key) use ($manager) {
            $setting = ($this->settingManager->create())
                ->setName($key)
                ->setValue($value);

            $this->addReference("setting_$key", $setting);
            $manager->persist($setting);
        });

        $manager->flush();
    }
}
