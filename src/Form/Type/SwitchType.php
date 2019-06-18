<?php

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class SwitchType.
 */
class SwitchType extends AbstractType
{
    /**
     * @return string|null
     */
    public function getParent()
    {
        return CheckboxType::class;
    }
}
