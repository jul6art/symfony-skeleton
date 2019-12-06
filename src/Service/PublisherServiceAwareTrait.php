<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Service;

/**
 * Trait PublisherServiceAwareTrait.
 */
trait PublisherServiceAwareTrait
{
    /**
     * @var PublisherService
     */
    protected $publisherService;

    /**
     * @return PublisherService
     */
    public function getPublisherService(): PublisherService
    {
        return $this->publisherService;
    }

    /**
     * @param PublisherService $publisherService
     *
     * @required
     */
    public function setPublisherService(PublisherService $publisherService): void
    {
        $this->publisherService = $publisherService;
    }
}
