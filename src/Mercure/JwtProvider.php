<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 30/11/2019
 * Time: 13:15.
 */

namespace App\Mercure;

/**
 * Class JwtProvider.
 */
class JwtProvider
{
    /**
     * @var string
     */
    private $token;

    /**
     * JwtProvider constructor.
     *
     * @param string $secret
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function __invoke(): string
    {
        return $this->token;
    }
}
