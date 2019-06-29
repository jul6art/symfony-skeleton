<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 29/06/2019
 * Time: 21:39
 */

namespace App\Form\User;

use Symfony\Component\Form\AbstractType;

/**
 * Class EditUserType
 * @package App\Form\User
 */
class EditUserType extends AbstractType {
	/**
	 * @return null|string
	 */
	public function getParent() {
		return AddUserType::class;
	}
}