<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\EntityListener;

use App\Manager\FunctionalityManagerTrait;
use App\Traits\FlashBagTrait;
use App\Traits\TranslatorTrait;

/**
 * Class AbstractEntityListener.
 */
abstract class AbstractEntityListener
{
    use FlashBagTrait;
    use FunctionalityManagerTrait;
    use TranslatorTrait;
}
