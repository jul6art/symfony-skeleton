<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Transformer;

use App\Entity\Test;
use App\Security\Voter\TestVoter;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class TestDataTableTransformer.
 */
class TestDataTableTransformer extends TestTransformer
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
     * @param mixed $test
     * @param null  $format
     * @param array $contexts
     *
     * @return array|bool|float|int|string
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function normalize($test, $format = null, array $contexts = [])
    {
        if (!$test instanceof Test) {
            return [];
        }

        $output = parent::normalize($test, $format, $contexts);

        if ($this->authorizationChecker->isGranted(TestVoter::VIEW, $test)) {
            $this->addAction(
                'view',
                'admin_test_view',
                [
                    'id' => $test->getId(),
                ],
                'table.actions.test.view',
                'visibility',
                'blue-grey'
            );
        }

        if ($this->authorizationChecker->isGranted(TestVoter::EDIT, $test)) {
            $this->addAction(
                'edit',
                'admin_test_edit',
                [
                    'id' => $test->getId(),
                ],
                'table.actions.test.edit',
                'edit',
                'theme'
            );
        }

        if ($this->authorizationChecker->isGranted(TestVoter::DELETE, $test)) {
            $this->addAction(
                'delete',
                'admin_test_delete',
                [
                    'id' => $test->getId(),
                ],
                'table.actions.test.delete',
                'remove_circle_outline',
                'black',
                [
                    'data-confirm' => 'confirm',
                    'data-dialog-cancel' => 'javascript.dialog.ajax.test.cancel',
                    'data-dialog-success' => 'javascript.dialog.ajax.test.success',
                    'data-dialog-confirm' => 'javascript.dialog.ajax.test.confirm',
                    'data-dialog-confirm-parameters' => [
                        'name' => $test->getName(),
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
        return $data instanceof Test;
    }
}
