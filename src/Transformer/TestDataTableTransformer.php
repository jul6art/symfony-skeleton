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
	 * @param mixed $test
	 * @param null $format
	 * @param array $context
	 *
	 * @return array|bool|float|int|string
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

        $this->init();

        $output = parent::normalize($test, $format, $context);

        if ($this->authorizationChecker->isGranted(TestVoter::VIEW, $test)) {
        	$this->addAction('view', [
        		'route' => 'admin_test_view',
		        'routeParams' => [
			        'id' => $test->getId(),
		        ],
		        'icon' => 'visibility',
		        'color' => 'blue-grey',
		        'label' => 'table.actions.test.view',
	        ]);
        }

        if ($this->authorizationChecker->isGranted(TestVoter::EDIT, $test)) {
        	$this->addAction('edit', [
        		'route' => 'admin_test_edit',
		        'routeParams' => [
			        'id' => $test->getId(),
		        ],
		        'icon' => 'edit',
		        'color' => 'theme',
		        'label' => 'table.actions.test.edit',
	        ]);
        }

        if ($this->authorizationChecker->isGranted(TestVoter::DELETE, $test)) {
        	$this->addAction('delete', [
        		'route' => 'admin_test_delete',
		        'routeParams' => [
			        'id' => $test->getId(),
		        ],
		        'icon' => 'remove_circle_outline',
		        'color' => 'black',
		        'label' => 'table.actions.test.delete',
	        ]);
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
