<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Traits;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Trait FlashBagTrait.
 */
trait FlashBagTrait
{
    /**
     * @var FlashBagInterface
     */
    protected $flashBag;

    /**
     * @return FlashBagInterface
     */
    public function getFlashBag(): FlashBagInterface
    {
        return $this->flashBag;
    }

    /**
     * @param FlashBagInterface $flashBag
     *
     * @required
     */
    public function setFlashBag(FlashBagInterface $flashBag): void
    {
        $this->flashBag = $flashBag;
    }
}
