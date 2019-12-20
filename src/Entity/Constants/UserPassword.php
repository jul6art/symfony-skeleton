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
 * Class UserPassword
 * @package App\Entity\Constants
 */
final class UserPassword
{
    public const USER_PASSWORD_LENGTH_MIN = 5;
    public const USER_PASSWORD_LENGTH_MAX = 32;
    public const USER_PASSWORD_LENGTH_GENERATED = 8;
    public const USER_PASSWORD_DEFAULT_VALUE = 'devinthehood';
}
