<?php

namespace App\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class WysiwygType.
 */
class WysiwygType extends AbstractType
{
    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars['max_length'] = $options['max_length'];
        $view->vars['min_length'] = $options['min_length'];
        $view->vars['upload'] = $options['upload'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined([
            'max_length',
            'min_length',
            'upload',
        ]);

        $resolver->setDefaults([
            'max_length' => false,
            'min_length' => false,
            'no_float' => true,
            'upload' => false,
        ]);
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return TextareaType::class;
    }
}
