<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Traits;

use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Trait MessageBusAwareTrait.
 */
trait MessageBusAwareTrait
{
    /**
     * @var MessageBusInterface
     */
    protected $bus;

    /**
     * @return MessageBusInterface
     */
    public function getBus(): MessageBusInterface
    {
        return $this->bus;
    }

    /**
     * @param MessageBusInterface $bus
     *
     * @required
     */
    public function setBus(MessageBusInterface $bus): void
    {
        $this->bus = $bus;
    }
}
