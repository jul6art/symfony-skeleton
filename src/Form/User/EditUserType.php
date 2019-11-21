<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Form\User;

use Symfony\Component\Form\AbstractType;

/**
 * Class EditUserType.
 */
class EditUserType extends AbstractType
{
    /**
     * @return string|null
     */
    public function getParent()
    {
        return AddUserType::class;
    }
}
