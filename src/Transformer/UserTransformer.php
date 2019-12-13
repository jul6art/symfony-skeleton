<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Transformer;

use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class UserTransformer.
 */
class UserTransformer implements NormalizerInterface
{
    use CellFormatterAwareTrait;

    /**
     * @param mixed $user
     * @param null  $format
     * @param array $contexts
     *
     * @return array|bool|float|int|string
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function normalize($user, $format = null, array $contexts = [])
    {
        if (!$user instanceof User) {
            return [];
        }

        return [
            'id' => $user->getId(),
            'gender' => $this->renderCell('user/cell/gender.html.twig', ['gender' => $user->getGender()]),
            'name' => $user->getFullname(),
            'username' => $user->getUsername(),
            'email' => $this->renderCellEmail($user->getEmail()),
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
