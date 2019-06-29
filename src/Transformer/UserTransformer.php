<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:09.
 */

namespace App\Transformer;

use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class UserTransformer.
 */
class UserTransformer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($user, $format = null, array $context = [])
    {
        if (!$user instanceof User) {
            return [];
        }

        return [
            'id' => $user->getId(),
            'name' => $user->getFullname(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof User;
    }
}
