<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 01/12/2019
 * Time: 02:40.
 */

namespace App\MessageHandler;

use App\Service\Traits\PublisherServiceAwareTrait;
use App\Traits\LoggerAwareTrait;

/**
 * Class AbstractMessageHandler.
 */
abstract class AbstractMessageHandler
{
    use LoggerAwareTrait;
    use PublisherServiceAwareTrait;
}
