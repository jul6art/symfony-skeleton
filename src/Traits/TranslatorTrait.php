<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Traits;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Trait TranslatorTrait.
 */
trait TranslatorTrait
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * @param TranslatorInterface $translator
     *
     * @required
     */
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }
}
