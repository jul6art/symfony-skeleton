<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Entity\Constants;

/**
 * Class SettingName
 * @package App\Entity\Constants
 */
final class SettingName
{
    public const SETTING_NAME_PROJECT_NAME = 'setting.project_name';
    public const SETTING_NAME_BASE_TITLE = 'setting.base_title';
    public const SETTING_NAME_DEFAULT_THEME = 'setting.default_theme';
    public const SETTING_NAME_AUDIT_LIMIT = 'setting.audit_limit';
    public const SETTING_NAME_TOASTR_VERTICAL_POSITION = 'setting.toastr_vertical_position';
    public const SETTING_NAME_TOASTR_HORIZONTAL_POSITION = 'setting.toastr_horizontal_position';

    public const SETTING_VALUE_PROJECT_NAME = 'Symfony skeleton';
    public const SETTING_VALUE_BASE_TITLE = ' | Symfony Skeleton';
    public const SETTING_VALUE_DEFAULT_THEME = 'blue';
    public const SETTING_VALUE_AUDIT_LIMIT = 200;
    public const SETTING_VALUE_TOASTR_VERTICAL_POSITION = 'bottom';
    public const SETTING_VALUE_TOASTR_HORIZONTAL_POSITION = 'center';
}
