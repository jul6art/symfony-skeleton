<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 11/06/2019
 * Time: 09:34.
 */

namespace App\Validator\Constraints;

use ReCaptcha\ReCaptcha as GoogleRecaptcha;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class RecaptchaValidator.
 */
class RecaptchaValidator extends ConstraintValidator
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $recaptcha_secret;

    /**
     * RecaptchaValidator constructor.
     *
     * @param RequestStack $requestStack
     * @param string       $recaptchaSecret
     */
    public function __construct(RequestStack $requestStack, string $recaptacha_secret)
    {
        $this->requestStack = $requestStack;
        $this->recaptcha_secret = $recaptacha_secret;
    }

	/**
	 * @param mixed $value
	 * @param Constraint $constraint
	 */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Recaptcha) {
            throw new UnexpectedTypeException($constraint, Recaptcha::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $this->recaptcha_secret || '' === $this->recaptcha_secret) {
            return;
        }

        $captcha = $this->requestStack->getMasterRequest()->get('g-recaptcha-response');
        $ip = $this->requestStack->getMasterRequest()->getClientIp();

        $recaptcha = new GoogleRecaptcha($this->recaptcha_secret);

        if (!$recaptcha->verify($captcha, $ip)->isSuccess()) {
            $this->context->buildViolation($constraint->message)
                          ->addViolation();
        }
    }
}
