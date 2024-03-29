<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Form\Type;

use App\Validator\Constraints\Range;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RangeType.
 */
class RangeType extends AbstractType
{
    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars['double'] = $options['double'];
        $view->vars['limit'] = $options['limit'];
        $view->vars['max'] = $options['max'];
        $view->vars['min'] = $options['min'];
        $view->vars['step'] = $options['step'];
        $view->vars['tooltips'] = $options['tooltips'];
        $view->vars['vertical'] = $options['vertical'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined([
            'double',
            'limit',
            'max',
            'min',
            'step',
            'tooltips',
            'vertical',
        ]);

        $resolver->setDefaults([
            'double' => false,
            'error_bubbling' => false,
            'limit' => false,
            'no_float' => true,
            'tooltips' => true,
            'vertical' => false,
            'constraints' => [
                new Range(),
            ],
        ]);
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return HiddenType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'custom_range';
    }
}
