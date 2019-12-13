<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\EntityListener;

use App\Manager\FunctionalityManagerAwareTrait;
use App\Traits\FlashBagAwareTrait;
use App\Traits\TranslatorAwareTrait;

/**
 * Class AbstractEntityListener.
 */
abstract class AbstractEntityListener
{
    use FlashBagAwareTrait;
    use FunctionalityManagerAwareTrait;
    use TranslatorAwareTrait;
}
