<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 30/11/2019
 * Time: 13:15.
 */

namespace App\Mercure;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

/**
 * Class JwtProvider.
 */
class JwtProvider
{
    /**
     * @var string
     */
    private $secret;

    /**
     * JwtProvider constructor.
     *
     * @param string $secret
     */
    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * @return string
     */
    public function __invoke(): string
    {
        $builder = (new Builder())
            ->set('mercure', ['publish' => ['*']])
            ->sign(new Sha256(), $this->secret);

        return (string) $builder->getToken();
    }
}
