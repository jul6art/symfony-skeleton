<?php

/**
 * Created by PhpStorm.
 * User: Jul6art
 * Date: 23/05/2019
 * Time: 09:09.
 */

namespace App\Transformer;

use App\Entity\User;
use App\Security\Voter\UserVoter;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class UserDataTableTransformer.
 */
class UserDataTableTransformer extends UserTransformer
{
    use CellFormatterTrait;
    use DataTableTransformerTrait;

    /**
     * Automatically called on each iteration
     * after each call to renderAction().
     */
    public function callback()
    {
    }

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

        $output = parent::normalize($user, $format, $contexts);

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

        if ($this->authorizationChecker->isGranted(UserVoter::IMPERSONATE, $user)) {
            $this->addAction(
                'impersonate',
                'admin_user_impersonate',
                [
                    'id' => $user->getId(),
                ],
                'table.actions.user.impersonate',
                'sync_alt',
                'red'
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
                    'data-dialog-cancel' => 'javascript.dialog.ajax.user.cancel',
                    'data-dialog-success' => 'javascript.dialog.ajax.user.success',
                    'data-dialog-confirm' => 'javascript.dialog.ajax.user.confirm',
                    'data-dialog-confirm-parameters' => [
                        'name' => $user->getFullname(),
                    ],
                ]
            );
        }

        $output['actions'] = $this->renderActions($this->actions, 'includes/datatable/actions/dropdown.html.twig');

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
