<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:09.
 */

namespace App\Transformer;

use App\Entity\User;
use App\Security\Voter\UserVoter;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class UserDataTableTransformer.
 */
class UserDataTableTransformer extends UserTransformer
{
    use DataTableTransformerTrait;

    /**
     * Automatically called on each iteration
     * after each call to renderAction().
     */
    public function reset()
    {
    }

    /**
     * @param mixed $user
     * @param null  $format
     * @param array $context
     *
     * @return array|bool|float|int|string
     *
     * @throws ExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function normalize($user, $format = null, array $context = [])
    {
        if (!$user instanceof User) {
            return [];
        }

        $output = parent::normalize($user, $format, $context);

        if ($this->authorizationChecker->isGranted(UserVoter::VIEW, $user)) {
            $this->addAction(
                'view',
                'admin_user_view',
                [
                    'id' => $user->getId(),
                ],
                'table.actions.user.view',
                'visibility',
                'blue-grey'
            );
        }

        if ($this->authorizationChecker->isGranted(UserVoter::EDIT, $user)) {
            $this->addAction(
                'edit',
                'admin_user_edit',
                [
                    'id' => $user->getId(),
                ],
                'table.actions.user.edit',
                'edit',
                'theme'
            );
        }

        if ($this->authorizationChecker->isGranted(UserVoter::DELETE, $user)) {
            $this->addAction(
                'delete',
                'admin_user_delete',
                [
                    'id' => $user->getId(),
                ],
                'table.actions.user.delete',
                'remove_circle_outline',
                'black',
                [
                    'data-confirm' => 'confirm',
                    'data-dialog-confirm' => 'dialog.ajax.user.confirm',
                    'data-dialog-cancel' => 'dialog.ajax.user.cancel',
                    'data-dialog-success' => 'dialog.ajax.user.success',
                ]
            );
        }

        $output['actions'] = $this->renderActions($this->actions, 'includes/datatable_actions_dropdown.html.twig');

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof User;
    }
}
