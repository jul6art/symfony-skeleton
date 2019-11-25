<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 25/11/2019
 * Time: 00:36.
 */

namespace App\Form\User;

use App\Entity\Group;
use App\Form\Type\EntityChoiceSimpleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GroupsType.
 */
class GroupsType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Group::class,
            'translation_domain' => 'form',
            'multiple' => true,
            'required' => true,
            'no_float' => true,
            'field' => 'name',
            'entity_label' => function ($group) {
                return 'form.user.groups.' . $group['name'];
            },
        ]);
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return EntityChoiceSimpleType::class;
    }
}
