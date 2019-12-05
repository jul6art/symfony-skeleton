<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Form\Test;

use App\Entity\Test;
use App\Form\Type\TextType;
use App\Form\Type\WysiwygType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddTestType.
 */
class AddTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'form.test.name.label',
                // test
                //
                'mask' => "'mask': '99-9999999'",
                'pattern' => '\d{2}[\-]\d{7}',
                'help' => 'form.test.name.help',
                'alert' => 'form.test.name.help',
                'tooltip' => 'form.test.name.help',
                'addon_left' => 'prepend',
                'button_right' => '<i class="fa fa-check-square-o"></i>',
                'button_right_class' => 'bg-blue test',
                //
                //  to override theme color
                  'alert_class' => 'bg-green',
                //
                //  to deactivate label floating effect
                  'no_float' => true,
            ])
            ->add('content', WysiwygType::class, [
                'label' => 'form.test.content.label',
                'required' => true,
                'min_length' => Test::TEXT_LENGTH,
                // test
                'help' => 'form.test.name.help',
            ])
            // remove this field to remove test fields
            ->add('test', TestTestType::class, [
                'mapped' => false,
                'disabled' => false,
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Test::class,
            'translation_domain' => 'form',
        ]);
    }
}
