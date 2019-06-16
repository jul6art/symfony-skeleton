<?php

namespace App\Form\Test;

use App\Entity\Test;
use App\Form\Type\BooleanType;
use App\Form\Type\PhoneType;
use App\Form\Type\RangeType;
use App\Form\Type\SwitchType;
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
                // test
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
                'required' => true,
                // test
                'help' => 'form.test.name.help',
            ])
            ->add('checkbox', CheckboxType::class, [
                'label' => 'Checkbox',
	            'mapped' => false,
                'required' => true,
	            // test
                'help' => 'form.test.name.help',
            ])
            ->add('boolean', BooleanType::class, [
                'label' => 'Boolean',
	            'mapped' => false,
                'required' => true,
	            // test
                'help' => 'form.test.name.help',
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
	            // test
                'help' => 'form.test.name.help',
            ])
            ->add('radio2', ChoiceType::class, [
                'label' => 'Radio exploded',
	            'mapped' => false,
                'required' => true,
	            'expanded' => true,
	            'choices' => [
	            	'foo' => 0,
		            'bar' => 1,
		            'baz' => 2,
	            ],
	            'attr' => [
	            	'class' => 'radio-block',
                ],
	            // test
                'help' => 'form.test.name.help',
            ])
            ->add('switch', SwitchType::class, [
	            'label' => 'Switch',
	            'mapped' => false,
	            'required' => true,
	            // test
	            'help' => 'form.test.name.help',
            ])
            ->add('range', RangeType::class, [
	            'label' => 'Range',
	            'mapped' => false,
	            'required' => true,
	            'min' => 0,
	            'max' => 100,
	            'step' => 10,
	            // test
	            'help' => 'form.test.name.help',
	            'data'  => 50,
            ])
            ->add('range2', RangeType::class, [
	            'label' => 'Range double',
	            'mapped' => false,
	            'required' => true,
	            'min' => 0,
	            'max' => 100,
	            'step' => 10,
	            'double' => true,
	            // test
	            'help' => 'form.test.name.help',
	            'data' => implode(', ', [
		            20,
		            80,
	            ]),
            ])
            ->add('phone', PhoneType::class, [
	            'label' => 'Phone',
	            'mapped' => false,
	            'required' => true,
	            // test
	            'help' => 'form.test.name.help',
            ])
            ->add('mobile', PhoneType::class, [
	            'label' => 'mobile phone',
	            'mapped' => false,
	            'required' => true,
	            'mobile' => true,
	            // test
	            'help' => 'form.test.name.help',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Test::class,
            'translation_domain' => 'form',
        ]);
    }
}
