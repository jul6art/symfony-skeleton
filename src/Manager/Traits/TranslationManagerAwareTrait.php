<?php

/**
 * Created by devinthehood.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Manager\Traits;

use App\Manager\TranslationManager;

/**
 * Trait TranslationManagerAwareTrait.
 */
trait TranslationManagerAwareTrait
{
    /**
     * @var TranslationManager
     */
    protected $translationManager;

    /**
     * @return TranslationManager
     */
    public function getTranslationManager(): TranslationManager
    {
        return $this->translationManager;
    }

    /**
     * @param TranslationManager $translationManager
     *
     * @required
     */
    public function setTranslationManager(TranslationManager $translationManager): void
    {
        $this->translationManager = $translationManager;
    }
}
