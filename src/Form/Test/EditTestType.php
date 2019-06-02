<?php

namespace App\Form\Test;

use Symfony\Component\Form\AbstractType;

/**
 * Class EditTestType.
 */
class EditTestType extends AbstractType
{
    /**
     * @return string|null
     */
    public function getParent()
    {
        return AddTestType::class;
    }
}
