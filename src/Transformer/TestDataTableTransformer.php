<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:09.
 */

namespace App\Transformer;

use App\Entity\Test;
use App\Security\Voter\TestVoter;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class TestDataTableTransformer.
 */
class TestDataTableTransformer extends TestTransformer implements NormalizerInterface
{
    use DataTableTransformerTrait;

    /**
     * Automatically called on each iteration
     * after each call to renderAction().
     */
    public function __reset()
    {
    }

    /**
     * @param mixed $test
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
    public function normalize($test, $format = null, array $context = [])
    {
        if (!$test instanceof Test) {
            return [];
        }

        $output = parent::normalize($test, $format, $context);

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
                    'data-dialog-confirm' => 'dialog.ajax.test.confirm',
                    'data-dialog-cancel' => 'dialog.ajax.test.cancel',
                    'data-dialog-success' => 'dialog.ajax.test.success',
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
        return $data instanceof Test;
    }
}
