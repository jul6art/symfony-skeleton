<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 29/06/2019
 * Time: 21:39
 */

namespace App\Form\User;

use App\Entity\User;
use App\Form\Type\EmailType;
use App\Form\Type\GenderType;
use App\Form\Type\PasswordType;
use App\Form\Type\RecaptchaType;
use App\Form\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegisterUserType
 * @package App\Form\User
 */
class RegisterUserType extends AbstractType {
	/**
	 * @var string
	 */
	private $environment;

	/**
	 * RegisterUserType constructor.
	 *
	 * @param string $environment
	 */
	public function __construct(string $environment) {
		$this->environment = $environment;
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('firstname', TextType::class, [
			'no_float' => true,
			'label' => false,
			'attr' => [
				'placeholder' => 'form.user.firstname.label',
			],
		])->add('lastname', TextType::class, [
			'no_float' => true,
			'label' => false,
			'attr' => [
				'placeholder' => 'form.user.lastname.label',
			],
		])->add('email', EmailType::class, [
			'addon_left' => '<i class="material-icons">mail_outline</i>',
			'no_float' => true,
			'label' => false,
			'attr' => [
                'autocomplete' => 'off',
				'placeholder' => 'form.user.email.label',
			],
		])->add('gender', GenderType::class, [
			'label' => false,
		])->add('username', TextType::class, [
			'no_float' => true,
			'label' => false,
			'attr' => [
				'autocomplete' => 'off',
				'placeholder' => 'form.user.username.label',
			],
		])->add('plainPassword', RepeatedType::class, [
			'type' => PasswordType::class,
			'first_options' => [
				'addon_left' => '<i class="material-icons">lock</i>',
				'label' => false,
				'attr' => [
					'autocomplete' => 'off',
					'class' => 'password',
					'placeholder' => 'form.user.password.label',
				],
			],
			'second_options' => [
				'addon_left' => '<i class="material-icons">lock</i>',
				'label' => false,
				'attr' => [
					'autocomplete' => 'off',
					'class' => 'password_verification',
					'placeholder' => 'form.user.password_confirmation.label',
				],
			],
			'invalid_message' => 'form.password_verification.error',
		]);

		if ($this->environment !== 'test') {
			$builder->add('captcha', RecaptchaType::class, [
				'label' => false,
				'mapped' => false,
				'required' => true,
			]);
		}
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'data_class' => User::class,
			'translation_domain' => 'form',
		]);
	}
}