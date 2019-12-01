<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Service;

use App\Entity\User;
use App\Traits\RouterTrait;
use App\Traits\SerializerTrait;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

/**
 * Class PublisherService.
 */
class PublisherService
{
    use RouterTrait;
    use SerializerTrait;

    /**
     * @var PublisherInterface
     */
    private $publisher;

    /**
     * @var string
     */
    protected $current_domain;

    /**
     * @var string
     */
    protected $http_protocol;

    /**
     * PublisherService constructor.
     *
     * @param PublisherInterface $publisher
     * @param string             $current_domain
     * @param string             $http_protocol
     */
    public function __construct(PublisherInterface $publisher, string $current_domain, string $http_protocol)
    {
        $this->publisher = $publisher;
        $this->current_domain = $current_domain;
        $this->http_protocol = $http_protocol;
    }

    /**
     * @param string $route
     * @param array  $routeParams
     * @param array  $data
     */
    public function publish(string $route, array $routeParams = [], array $data = [], array $targets = []): void
    {
        $data['targets'] = array_map(function (User $user) {
            return sprintf(
                '%s%s%s',
                $this->http_protocol,
                $this->current_domain,
                $this->router->generate('admin_user_view', ['id' => $user->getId()])
            );
        }, $targets);

        $publisher = $this->publisher;
        $publisher(new Update(
            sprintf(
                '%s%s%s',
                $this->http_protocol,
                $this->current_domain,
                $this->router->generate($route, $routeParams)
            ),
            $this->serializer->serialize($data, 'json')
        ));
    }
}
