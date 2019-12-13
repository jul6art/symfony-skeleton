<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class EntityToIdTransformer.
 */
class EntityToIdTransformer implements DataTransformerInterface
{
    private $entityRepository;

    /**
     * EntityToIdTransformer constructor.
     *
     * @param ObjectRepository $entityRepository
     */
    public function __construct(ObjectRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * @param object|array $entity
     *
     * @return int|int[]
     *
     * @throws TransformationFailedException
     */
    public function transform($entity)
    {
        if (null === $entity) {
            return null;
        }

        if (\is_iterable($entity)) {
            $idList = [];

            foreach ($entity as $item) {
                $idList[] = $item->getId();
            }

            return $idList;
        }

        if (\is_object($entity)) {
            return $entity->getId();
        }

        throw new TransformationFailedException(sprintf(
            '%s(%s) is not a valid class for EntityToIdTransformer',
            (\is_object($entity) ? \get_class($entity) : ''),
            \gettype($entity)
        ));
    }

    /**
     * @param int|array $id
     *
     * @return object|object[]
     *
     * @throws TransformationFailedException
     */
    public function reverseTransform($id)
    {
        if (null === $id) {
            return null;
        }

        if (\is_numeric($id)) {
            $entity = $this->entityRepository->findOneBy(['id' => $id]);

            if (null === $entity) {
                throw new TransformationFailedException(sprintf(
                    'A %s with id #%s does not exist!',
                    $this->entityRepository->getClassName(),
                    $id
                ));
            }

            return $entity;
        }

        if (\is_array($id)) {
            if (empty($id)) {
                return [];
            }

            $entities = $this->entityRepository->findBy(['id' => $id]);

            if (\count($id) !== \count($entities)) {
                throw new TransformationFailedException(sprintf(
                    'Some %s with id #%s do not exist!',
                    $this->entityRepository->getClassName(),
                    implode(', ', $id)
                ));
            }

            return $entities;
        }

        throw new TransformationFailedException(sprintf(
            '%s is not a valid type for EntityToIdTransformer',
            \gettype($id)
        ));
    }
}
