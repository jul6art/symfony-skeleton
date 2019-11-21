<?php

/**
 * Created by VsWeb.
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
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ChangePasswordType.
 */
class ChangePasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraintsOptions = [
            'message' => 'fos_user.current_password.invalid',
        ];

        if (!empty($options['validation_groups'])) {
            $constraintsOptions['groups'] = [
                reset($options['validation_groups']),
            ];
        }

        $builder->add('current_password', PasswordType::class, array(
            'label' => false,
            'mapped' => false,
            'constraints' => array(
                new NotBlank(),
                new UserPassword($constraintsOptions),
            ),
            'addon_left' => '<i class="material-icons">lock</i>',
            'attr' => array(
                'autocomplete' => 'current-password',
                'placeholder' => 'form.user.current_password.label',
            ),
        ));

        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'options' => [
                'attr' => [
                    'autocomplete' => 'new-password',
                ],
            ],
            'first_options' => [
                'addon_left' => '<i class="material-icons">lock</i>',
                'label' => false,
                'attr' => [
                    'autocomplete' => false,
                    'class' => 'password',
                    'placeholder' => 'form.user.new_password.label',
                ],
            ],
            'second_options' => [
                'addon_left' => '<i class="material-icons">lock</i>',
                'label' => false,
                'attr' => [
                    'autocomplete' => false,
                    'class' => 'password_verification',
                    'placeholder' => 'form.user.new_password_confirmation.label',
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
