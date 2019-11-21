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
use App\Form\Type\EmailType;
use App\Form\Type\GenderType;
use App\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddUserType.
 */
class AddUserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', TextType::class, [
            'label' => 'form.user.firstname.label',
        ])->add('lastname', TextType::class, [
            'label' => 'form.user.lastname.label',
        ])->add('email', EmailType::class, [
            'addon_left' => '<i class="material-icons">mail_outline</i>',
            'label' => 'form.user.email.label',
        ])->add('gender', GenderType::class, [
            'label' => 'form.user.gender.label',
        ])->add('username', TextType::class, [
            'label' => 'form.user.username.label',
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
