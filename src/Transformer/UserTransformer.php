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
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class UserTransformer.
 */
class UserTransformer implements NormalizerInterface
{
	use CellFormatTransformerTrait;

	/**
	 * @param mixed $user
	 * @param null $format
	 * @param array $context
	 *
	 * @return array|bool|float|int|string
	 * @throws LoaderError
	 * @throws RuntimeError
	 * @throws SyntaxError
	 */
    public function normalize($user, $format = null, array $context = [])
    {
        if (!$user instanceof User) {
            return [];
        }

        return [
            'id' => $user->getId(),
            'gender' => $this->renderCellLabel('table.user.gender.' . $user->getGender()),
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
