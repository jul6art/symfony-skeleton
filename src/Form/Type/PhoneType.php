<?php

namespace App\Form\Type;

use App\Validator\Constraints\Phone;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PhoneType.
 */
class PhoneType extends AbstractType
{
    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars['mobile'] = $options['mobile'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined([
            'mobile',
        ]);

        $resolver->setDefaults([
            'no_float' => true,
            'mobile' => false,
            'attr' => [
                'class' => 'input-phone',
            ],
            'constraints' => [
                new Phone(),
            ],
        ]);
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return TextType::class;
    }
}
