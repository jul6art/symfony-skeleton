<?php

namespace App\Form\Test;

use App\Entity\Test;
use App\Form\Type\BooleanType;
use App\Form\Type\ChoiceType;
use App\Form\Type\DatePickerType;
use App\Form\Type\DatetimePickerType;
use App\Form\Type\GenderType;
use App\Form\Type\PhoneType;
use App\Form\Type\RangeType;
use App\Form\Type\SwitchType;
use App\Form\Type\TimePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
		    // remove this field to remove test fields
		    ->add('test', TestTestType::class, [
		    	'mapped' => false,
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
