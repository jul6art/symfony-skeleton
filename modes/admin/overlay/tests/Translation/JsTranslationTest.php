<?php

declare(strict_types=1);

namespace App\Tests\Translation;

use Jul6Art\CoreBundle\Test\AbstractJsTranslationTestCase;
use Override;
use PHPUnit\Framework\Attributes\CoversNothing;

/**
 * Le garde du domaine `javascript` : les deux moitiés d'un libellé lu par le navigateur.
 *
 * ## Ce qu'il empêche
 *
 * Une chaîne affichée par du JavaScript n'a pas de sortie observable depuis PHP : elle n'apparaît
 * dans aucun gabarit, aucun test fonctionnel ne la rend, et une clé qui manque s'affiche
 * simplement telle quelle à l'écran. C'est ainsi que trois back-offices de cet écosystème ont
 * annoncé « bulk.select_all » à un lecteur d'écran pendant des semaines, avec une suite verte.
 *
 * Depuis que le catalogue est la source unique, la clé lue par le JavaScript EST la clé du
 * catalogue, et ce fichier tient les deux bouts :
 *
 * - toute clé lue par un `t()` / `trans()` du JavaScript est traduite, dans CHAQUE locale activée ;
 * - aucune clé du domaine n'est lue par personne — elle partirait dans le paquet de chaque
 *   visiteur, et rien d'autre dans la chaîne ne la mentionnerait jamais ;
 * - aucun gabarit ne passe de libellés au JavaScript par un attribut HTML ;
 * - aucun appelant serveur ne demande une clé de ce domaine à un autre catalogue.
 *
 * ## Ce qu'il faut lui ajouter
 *
 * Une clé que le code construit — `` t(`status.${value}`) `` — est invisible au scanner. Déclarez
 * ces cas dans {@see self::declaredKeys()}, dérivés de l'énumération plutôt que recopiés ; ou,
 * quand la clé est facultative (le code retombe sur un texte générique), dans
 * {@see self::declaredPrefixes()}.
 */
#[CoversNothing]
final class JsTranslationTest extends AbstractJsTranslationTestCase
{
    /**
     * ⚠️ Ajoutez ici le répertoire `assets/` de tout bundle qui livre des contrôleurs Stimulus :
     * les clés qu'ils lisent sont traduites par CE catalogue, et ne regarder que le code du projet
     * laisserait sans garde la moitié de ce qui s'affiche.
     *
     * @return list<string>
     */
    #[Override]
    protected static function javaScriptDirectories(): array
    {
        return [self::projectDir().'/assets'];
    }

    /**
     * Le garde anti-retour : aucun gabarit ne passe de libellés au JavaScript par un attribut.
     *
     * @return list<string>
     */
    #[Override]
    protected static function templateDirectories(): array
    {
        return [self::projectDir().'/templates'];
    }

    /**
     * Le garde de l'autre moitié d'un déplacement de domaine : les appelants SERVEUR.
     *
     * ⚠️ Un vocabulaire déplacé vers `javascript` est souvent rendu par Twig ou par PHP aussi —
     * une pastille sur la fiche, une option dans un filtre, le libellé d'une action de ligne. Un
     * `|trans` resté sur l'ancien domaine NE LÈVE PAS : il rend la CLÉ, en toutes lettres, dans
     * la page. Le défaut a traversé trois projets de cet écosystème avant que ce garde existe.
     *
     * @return list<string>
     */
    #[Override]
    protected static function serverDirectories(): array
    {
        return [self::projectDir().'/templates', self::projectDir().'/src'];
    }
}
