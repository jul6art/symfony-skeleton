<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 02/04/2019
 * Time: 22:06.
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
        } elseif (\is_iterable($entity)) {
            $idList = array();

            foreach ($entity as $item) {
                $idList[] = $item->getId();
            }

            return $idList;
        } elseif (\is_object($entity)) {
            return $entity->getId();
        }

        throw new TransformationFailedException((\is_object($entity) ? get_class($entity) : '').'('.gettype($entity).') is not a valid class for EntityToIdTransformer');
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
        } elseif (\is_numeric($id)) {
            $entity = $this->entityRepository->findOneBy(array('id' => $id));

            if (null === $entity) {
                throw new TransformationFailedException('A '.$this->entityRepository->getClassName().' with id #'.$id.' does not exist!');
            }

            return $entity;
        } elseif (\is_array($id)) {
            if (empty($id)) {
                return array();
            }

            $entities = $this->entityRepository->findBy(array('id' => $id)); // its array('id' => array(...)), resulting in many entities!!

            if (\count($id) != \count($entities)) {
                throw new TransformationFailedException('Some '.$this->entityRepository->getClassName().' with id #'.implode(', ', $id).' do not exist!');
            }

            return $entities;
        }

        throw new TransformationFailedException(gettype($id).' is not a valid type for EntityToIdTransformer');
    }
}
