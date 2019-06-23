<?php

namespace App\Form\Test;

use App\Form\DataTransformer\RangeDoubleTransformer;
use App\Form\DataTransformer\RangeTransformer;
use App\Form\Type\BooleanType;
use App\Form\Type\CheckboxType;
use App\Form\Type\ChoiceType;
use App\Form\Type\DatePickerType;
use App\Form\Type\DatetimePickerType;
use App\Form\Type\GenderType;
use App\Form\Type\PhoneType;
use App\Form\Type\RangeType;
use App\Form\Type\RecaptchaType;
use App\Form\Type\SwitchType;
use App\Form\Type\TextareaType;
use App\Form\Type\TimePickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TestTestType.
 */
class TestTestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('checkbox', CheckboxType::class, [
                'label' => 'Checkbox',
                'mapped' => false,
                'required' => true,
                // test
                'help' => 'form.test.name.help',
            ])
            ->add('textarea', TextareaType::class, [
                'label' => 'Textarea',
                'mapped' => false,
                'required' => true,
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
            ->add('boolean', BooleanType::class, [
                'label' => 'Boolean',
                'mapped' => false,
                'required' => true,
                // test
                'help' => 'form.test.name.help',
            ])
            ->add('boolean2', BooleanType::class, [
                'label' => 'Boolean exploded',
                'mapped' => false,
                'required' => true,
                'exploded' => true,
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
                'exploded' => true,
                'choices' => [
                    'foo' => 0,
                    'bar' => 1,
                    'baz' => 2,
                ],
                // test
                'help' => 'form.test.name.help',
            ])
            ->add('gender', GenderType::class, [
                'label' => 'Gender',
                'mapped' => false,
                'required' => true,
                // test
                'help' => 'form.test.name.help',
            ])
            ->add('gender2', GenderType::class, [
                'exploded' => true,
                'label' => 'Gender exploded',
                'mapped' => false,
                'required' => true,
                // test
                'help' => 'form.test.name.help',
            ])
            ->add('phone', PhoneType::class, [
                'label' => 'Phone',
                'mapped' => false,
                'required' => true,
                // test
                'help' => 'form.test.name.help',
            ])
            ->add('mobile', PhoneType::class, [
                'label' => 'Mobile phone',
                'mapped' => false,
                'required' => true,
                'mobile' => true,
                // test
                'help' => 'form.test.name.help',
            ]);

        $builder
            ->add('range', RangeType::class, [
                'label' => 'Range',
                'mapped' => false,
                'required' => true,
                'min' => 0,
                'max' => 100,
                'step' => 10,
                // test
                'help' => 'form.test.name.help',
                'data' => 50,
            ]);
        $builder->get('range')->addModelTransformer(new RangeTransformer());

        $builder
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
                'data' => [
                    10,
                    50,
                ],
            ]);
        $builder->get('range2')->addModelTransformer(new RangeDoubleTransformer());

        $builder
            ->add('range3', RangeType::class, [
                'label' => 'Range vertical',
                'mapped' => false,
                'required' => true,
                'min' => 0,
                'max' => 100,
                'step' => 10,
                'vertical' => true,
                // test
                'help' => 'form.test.name.help',
                'data' => 50,
            ]);
        $builder->get('range3')->addModelTransformer(new RangeTransformer());

        $builder
            ->add('range4', RangeType::class, [
                'label' => 'Range double vertical',
                'mapped' => false,
                'required' => true,
                'min' => 0,
                'max' => 100,
                'step' => 10,
                'double' => true,
                'vertical' => true,
                // test
                'help' => 'form.test.name.help',
                'data' => [
                    20,
                    40,
                    60,
                    80,
                ],
            ]);
        $builder->get('range4')->addModelTransformer(new RangeDoubleTransformer());

        $builder
            ->add('date', DatePickerType::class, [
                'label' => 'Date',
                'mapped' => false,
                'required' => true,
                'addon_right' => '<i class="fa fa-calendar"></i>',
                'minDate' => '01-08-2019',
                'maxDate' => '15-08-2019',
                'disabledDays' => json_encode([1, 2, 7]), // monday, tuesday, sunday (from 1 to 7)
                // test
                'help' => 'form.test.name.help',
            ])
            ->add('time', TimePickerType::class, [
                'label' => 'Time',
                'mapped' => false,
                'required' => true,
                'addon_left' => '<input type="checkbox" class="filled-in" id="ig_checkbox" name="NAME_REQUIRED_FOR_ARE_YOU_SURE"><label for="ig_checkbox"></label>',
                'addon_right' => '<i class="fa fa-clock-o"></i>',
                // test
                'help' => 'form.test.name.help',
            ])
            ->add('datetime', DatetimePickerType::class, [
                'label' => 'Datetime',
                'mapped' => false,
                'required' => true,
                'addon_right' => '<i class="fa fa-calendar"></i> <i class="fa fa-clock-o"></i>',
                'minDate' => '01-08-2019',
                'maxDate' => '15-08-2019',
                'disabledDays' => json_encode([1, 2, 7]), // monday, tuesday, sunday (from 1 to 7)
                // test
                'help' => 'form.test.name.help',
            ])
            ->add('captcha', RecaptchaType::class, [
                'label' => 'REcaptcha',
                'mapped' => false,
                'required' => true,
                'no_line' => false,
                // test
                'help' => 'form.test.name.help',
            ]);

        // $builder->setDisabled(true);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'form',
        ]);
    }
}
