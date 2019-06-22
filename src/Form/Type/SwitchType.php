<?php

namespace App\Form\Type;

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
