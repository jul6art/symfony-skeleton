<?php
/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 23/05/2019
 * Time: 09:41.
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
    public function setFlashBag( FlashBagInterface $flashBag): void
    {
        $this->flashBag = $flashBag;
    }
}
