<?php

namespace App\Form\Test;

use App\Entity\Test;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'attr' => [
                    'data-inputmask' => "'mask': '99-9999999'",
                    'pattern' => '\d{2}[\-]\d{7}',

                    'data-toggle' => 'tooltip',
                    'data-original-title' => 'Je ne suis pas encore traduit',

                    'data-alert' => 'form.test.name.help',
                    'data-alert-class' => 'info',
                ],

                'help' => 'form.test.name.help',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Test::class,
            'translation_domain' => 'form',
        ]);
    }
}
