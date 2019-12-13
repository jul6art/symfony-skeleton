<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Form\Type;

use App\Validator\Constraints\Datetime;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DatetimePickerType.
 */
class DatetimePickerType extends AbstractType
{
    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars['disabledDays'] = $options['disabledDays'];
        $view->vars['minDate'] = $options['minDate'];
        $view->vars['maxDate'] = $options['maxDate'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined([
            'disabledDays',
            'minDate',
            'maxDate',
        ]);

        $resolver->setDefaults([
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy HH:mm',
            'disabledDays' => false,
            'minDate' => false,
            'maxDate' => false,
            'constraints' => [
                new Datetime(),
            ],
        ]);
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return DateTimeType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'datetime_picker';
    }
}
