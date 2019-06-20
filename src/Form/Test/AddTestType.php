<?php

namespace App\Form\Test;

use App\Entity\Test;
use App\Form\Type\TextType;
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
			    'addon_left' => 'pre',
			    'addon_right' => 'form.test.name.help',
			    //
			    //  to override theme color
			      'alert_class' => 'bg-green',
			    //
			    //  to deactivate label floating effect
			      'no_float' => true,
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
