<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Form\Maintenance;

use Symfony\Component\Form\AbstractType;

/**
 * Class EditMaintenanceType.
 */
class EditMaintenanceType extends AbstractType
{
    /**
     * @return string|null
     */
    public function getParent()
    {
        return AddMaintenanceType::class;
    }
}
