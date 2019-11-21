<?php

/**
 * Created by PhpStorm.
 * User: Jul6art
 * Date: 23/05/2019
 * Time: 09:41.
 */

namespace App\Traits;

use Symfony\Component\Routing\RouterInterface;

/**
 * Trait RouterTrait.
 */
trait RouterTrait
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * @param RouterInterface $router
     *
     * @required
     */
    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }
}
