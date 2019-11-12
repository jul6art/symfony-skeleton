<?php

/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 29/06/2019
 * Time: 21:42.
 */

namespace App\Form\Maintenance;

use App\Entity\Maintenance;
use App\Form\Type\SwitchType;
use App\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddMaintenanceType.
 */
class AddMaintenanceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('active', SwitchType::class, [
            'label' => 'form.maintenance.active.label',
            'required' => false,
            'no_float' => true,
            'no_line' => true,
            'help' => 'form.maintenance.active.help',
        ]);

        $builder->get('active')
                ->addModelTransformer(new CallbackTransformer(
                    function ($active) {
                        return !$active;
                    },
                    function ($active) {
                        return !$active;
                    }
                ))
        ;

        $builder->add('exceptionIpList', TextType::class, [
            'label' => 'form.maintenance.exception_ip_list.label',
            'required' => false,
            'no_float' => true,
            'data' => $options['data']->getExceptionIpList(),
        ]);

        $builder->get('exceptionIpList')
            ->addModelTransformer(new CallbackTransformer(
                function ($exceptionIpList) {
                    return implode(', ', $exceptionIpList);
                },
                function ($exceptionIpList) {
                    return explode(', ', $exceptionIpList);
                }
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Maintenance::class,
            'translation_domain' => 'form',
        ]);
    }
}
