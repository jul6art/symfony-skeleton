<?php

namespace App\Form\Type;

use App\Entity\User;
use App\Validator\Constraints\Gender;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GenderType.
 */
class GenderType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'expanded' => true,
            'choices' => [
                'form.user.gender.choices.m' => User::GENDER_MALE,
                'form.user.gender.choices.f' => User::GENDER_FEMALE,
            ],
            'choice_translation_domain' => 'form',
            'constraints' => [
                new Gender(),
            ],
        ]);
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
