<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType as BaseType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BooleanType.
 */
abstract class AbstractType extends BaseType
{
    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['addon_left'] = $options['addon_left'];
        $view->vars['addon_right'] = $options['addon_right'];
        $view->vars['alert'] = $options['alert'];
        $view->vars['alert_class'] = $options['alert_class'];
        $view->vars['button_left'] = $options['button_left'];
        $view->vars['button_left_class'] = $options['button_left_class'];
        $view->vars['button_right'] = $options['button_right'];
        $view->vars['button_right_class'] = $options['button_right_class'];
        $view->vars['exploded'] = $options['exploded'];
        $view->vars['mask'] = $options['mask'];
        $view->vars['no_float'] = $options['no_float'];
        $view->vars['no_line'] = $options['no_line'];
        $view->vars['pattern'] = $options['pattern'];
        $view->vars['readonly'] = $options['readonly'];
        $view->vars['tooltip'] = $options['tooltip'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined([
            'addon_left',
            'addon_right',
            'alert',
            'alert_class',
            'button_left',
            'button_left_class',
            'button_right',
            'button_right_class',
            'exploded',
            'mask',
            'no_float',
            'no_line',
            'pattern',
            'readonly',
            'tooltip',
        ]);

        $resolver->setDefaults([
            'addon_left' => false,
            'addon_right' => false,
            'alert' => false,
            'alert_class' => false,
            'button_left' => false,
            'button_left_class' => false,
            'button_right' => false,
            'button_right_class' => false,
            'exploded' => false,
            'mask' => false,
            'no_float' => false,
            'no_line' => false,
            'pattern' => false,
            'readonly' => false,
            'tooltip' => false,
        ]);
    }
}
