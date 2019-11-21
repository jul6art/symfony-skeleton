<?php

/**
 * Created by PhpStorm.
 * User: Jul6art
 * Date: 22/07/2019
 * Time: 20:28.
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
