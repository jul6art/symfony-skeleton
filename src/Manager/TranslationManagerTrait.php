<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
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
