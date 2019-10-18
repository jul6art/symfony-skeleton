<?php

/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 07/02/2019
 * Time: 18:39.
 */

namespace App\Form\Type;

use App\Form\DataTransformer\EntityToIdTransformer;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EntityChoiceSimpleType.
 */
class EntityChoiceSimpleType extends AbstractType
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * EntityChoiceSimpleType constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(
            new EntityToIdTransformer($options['em']->getRepository($options['class']))
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $registry = $this->registry;
        $resolver->setDefaults([
            'empty_value' => false,
            'empty_data' => null,
            'em' => null,
            'query_builder' => null,
            'field' => 'id',
        ]);

        $resolver->setRequired([
            'class',
            'entity_label',
        ]);

        $resolver->setDefault('entity_group_by', null);

        $resolver->setDefault('choices', function (Options $options) use ($registry) {
            if (null === $options['query_builder']) {
                $results = $options['em']
                    ->createQueryBuilder()
                    ->select('e.id', 'e.' . $options['field'])
                    ->from($options['class'], 'e', 'e.id')
                    ->orderBy('e.' . $options['field'], 'ASC')
                    ->getQuery()
                    ->getArrayResult()
                ;
            } else {
                $results = $options['query_builder']
                    ->getQuery()
                    ->getArrayResult()
                ;
            }

            $return = [];

            if (null === $options['entity_group_by']) {
                array_map(function ($item) use (&$return, $options) {
                    $return[call_user_func($options['entity_label'], $item)] = $item['id'];
                }, $results);
            } else {
                array_map(function ($item) use (&$return, $options) {
                    $return[$item['type']][call_user_func($options['entity_label'], $item)] = $item['id'];
                }, $results);
            }

            return $return;
        });

        $queryBuilderNormalizer = function (Options $options, $queryBuilder) {
            if (\is_callable($queryBuilder)) {
                $queryBuilder = call_user_func($queryBuilder, $options['em']->getRepository($options['class']));
            }

            return $queryBuilder;
        };

        $emNormalizer = function (Options $options, $em) use ($registry) {
            /* @var ManagerRegistry $registry */
            if (null !== $em) {
                if ($em instanceof ObjectManager) {
                    return $em;
                }

                return $registry->getManager($em);
            }
            $em = $registry->getManagerForClass($options['class']);
            if (null === $em) {
                throw new \Exception(sprintf(
                    'Class "%s" seems not to be a managed Doctrine entity. ' .
                    'Did you forget to map it?',
                    $options['class']
                ));
            }

            return $em;
        };

        $resolver->setNormalizer('em', $emNormalizer);
        $resolver->setNormalizer('query_builder', $queryBuilderNormalizer);
    }

    /**
     * @return string|null
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}
