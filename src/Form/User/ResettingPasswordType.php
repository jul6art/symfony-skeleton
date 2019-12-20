<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Form\User;

use App\Entity\User;
use App\Form\Type\PasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Class ResettingPasswordType.
 */
class ResettingPasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'options' => [
                'constraints' => [
                    new Length([
                        'min' => \App\Entity\Constants\UserPassword::USER_PASSWORD_LENGTH_MIN,
                        'max' => \App\Entity\Constants\UserPassword::USER_PASSWORD_LENGTH_MAX,
                    ]),
                ],
            ],
            'first_options' => [
                'addon_left' => '<i class="material-icons">lock</i>',
                'label' => false,
                'attr' => [
                    'autocomplete' => 'off',
                    'class' => 'password',
                    'placeholder' => 'form.user.password.label',
                    'minLength' => \App\Entity\Constants\UserPassword::USER_PASSWORD_LENGTH_MIN,
                    'maxLength' => \App\Entity\Constants\UserPassword::USER_PASSWORD_LENGTH_MAX,
                ],
            ],
            'second_options' => [
                'addon_left' => '<i class="material-icons">lock</i>',
                'label' => false,
                'attr' => [
                    'autocomplete' => 'off',
                    'class' => 'password_verification',
                    'placeholder' => 'form.user.password_confirmation.label',
                    'minLength' => \App\Entity\Constants\UserPassword::USER_PASSWORD_LENGTH_MIN,
                    'maxLength' => \App\Entity\Constants\UserPassword::USER_PASSWORD_LENGTH_MAX,
                ],
            ],
            'invalid_message' => 'form.password_verification.error',
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'form',
        ]);
    }
}
