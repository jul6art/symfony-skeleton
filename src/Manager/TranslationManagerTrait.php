<?php

/**
 * Created by PhpStorm.
 * Audit: gkratz
 * Date: 23/05/2019
 * Time: 09:41.
 */

namespace App\Manager;

/**
 * Trait TranslationManagerTrait.
 */
trait TranslationManagerTrait
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
