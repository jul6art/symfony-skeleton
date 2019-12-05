<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 01/12/2019
 * Time: 02:40.
 */

namespace App\MessageHandler;

use App\Service\PublisherServiceTrait;
use App\Traits\LoggerTrait;

/**
 * Class AbstractMessageHandler.
 */
abstract class AbstractMessageHandler
{
    use LoggerTrait;
    use PublisherServiceTrait;
}
