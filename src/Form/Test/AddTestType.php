<?php

namespace App\Form\Test;

use App\Entity\Test;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
                    //'data-alert-class' => 'bg-blue',
                ],

                'help' => 'form.test.name.help',
            ])
            ->add('textarea', TextareaType::class, [
                'label' => 'Textarea',
	            'mapped' => false,
            ])
            ->add('checkbox', CheckboxType::class, [
                'label' => 'Checkbox',
	            'mapped' => false,
            ])
            ->add('radio', ChoiceType::class, [
                'label' => 'Radio',
	            'mapped' => false,
	            'expanded' => true,
	            'choices' => [ 
	            	'foo' => 0,
		            'bar' => 1,
		            'baz' => 2,
	            ],
            ])
            ->add('radio2', ChoiceType::class, [
                'label' => 'Radio exploded',
	            'mapped' => false,
	            'expanded' => true,
	            'choices' => [
	            	'foo' => 0,
		            'bar' => 1,
		            'baz' => 2,
	            ],
	            'attr' => [
	            	'class' => 'radio-block'
	            ],
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
