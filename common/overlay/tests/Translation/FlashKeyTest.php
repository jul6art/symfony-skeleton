<?php

declare(strict_types=1);

namespace App\Tests\Translation;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use function dirname;
use function sprintf;

/**
 * Les flashes sortent des contrôleurs **déjà traduits** : `addSuccessFlash('flash.x')` passe par
 * `Jul6Art\CoreBundle\Service\FlashTranslator`.
 *
 * Une clé absente du catalogue ne lève rien — le traducteur rend la clé, et c'est la clé que
 * l'utilisateur lit. Ce test compare donc les clés écrites dans les contrôleurs au catalogue.
 */
final class FlashKeyTest extends KernelTestCase
{
    public function testEveryFlashKeyExistsInTheCatalogue(): void
    {
        self::bootKernel();

        $translator = static::getContainer()->get(TranslatorInterface::class);
        self::assertInstanceOf(TranslatorBagInterface::class, $translator);

        // ⚠️ TOUTES les langues, pas seulement celle par défaut. Un catalogue vérifié seul laisse
        // l'autre diverger, et le traducteur retombe SILENCIEUSEMENT sur le repli : l'utilisateur
        // qui a choisi le français lit l'anglais, ou la clé. Vérifié le 2026-08-24 : retirer une
        // clé de `messages.fr.yaml` laissait ce test vert, puisque le défaut est `en`.
        // Les langues sont celles que le projet active (`framework.enabled_locales`) : ce fichier est
        // commun à tous les modes, et seul `backoffice` a sa liste `BackofficeLocales`, qu'il lit dans
        // sa propre version de ce test.
        $locales = static::getContainer()->getParameter('kernel.enabled_locales');
        self::assertNotSame([], $locales, 'Aucune langue activée : framework.enabled_locales est vide.');
        $missing = [];

        foreach ($locales as $locale) {
            $catalogue = $translator->getCatalogue($locale);

            foreach ($this->flashKeys() as $file => $keys) {
                foreach ($keys as $key) {
                    // ⚠️ `defines()` et NON `has()` : `has()` suit la chaîne de repli, donc une clé
                    // absente de `messages.fr.yaml` mais présente en `en` lui répond « oui ». Le
                    // test restait vert sur la locale non-défaut pendant que l'écran français
                    // affichait l'anglais — c'est-à-dire précisément ce qu'il doit attraper.
                    if (!$catalogue->defines($key, 'messages')) {
                        $missing[] = sprintf('[%s] %s : « %s »', $locale, $file, $key);
                    }
                }
            }
        }

        // Un squelette d'API n'émet aucun flash : rien à vérifier n'est un succès, pas un échec.
        self::assertSame([], $missing, "Clés de flash absentes du catalogue :\n".implode("\n", $missing));
    }

    /**
     * @return array<string, list<string>> fichier → clés littérales
     */
    private function flashKeys(): array
    {
        $root = dirname(__DIR__, 2);

        // ⚠️ Les contrôleurs des BUNDLES posent eux aussi des flashes, et c'est au PROJET de les
        // traduire : le partial de toasts d'`admin-bundle` résout dans `messages`, quel que soit
        // le domaine où la clé est déclarée. Ne scanner que `src/` laissait passer
        // `appearance.flash.saved` — émise par `Jul6Art\AdminBundle\Controller\AppearanceController`,
        // déclarée dans le domaine `appearance`, donc affichée BRUTE à l'écran (vu le 2026-08-23,
        // et superp l'affichait brute aussi, pour la même raison).
        $directories = array_values(array_filter([
            $root.'/src/Controller',
            $root.'/vendor/jul6art/admin-bundle/Controller',
        ], is_dir(...)));

        if ([] === $directories) {
            return [];
        }

        $keys = [];

        foreach (Finder::create()->files()->in($directories)->name('*.php') as $file) {
            $source = $file->getContents();

            // La quote fermante suivie d'une virgule ou d'une parenthèse : une clé concaténée
            // ('advertisement.'.$status) n'est pas lisible statiquement et reste hors de portée.
            // Les deux API coexistent : `addSuccessFlash('clé')` (core-bundle) et le
            // `addFlash('type', 'clé')` natif de Symfony — la clé est alors le DEUXIÈME argument.
            // Ne scanner que la première rendait ce test aveugle sur un projet qui n'utilise que
            // la seconde : zéro clé trouvée, suite verte, clé brute à l'écran (vu le 2026-08-23).
            preg_match_all(
                '/\$this->add(?:Success|Error|Warning)Flash\(\s*\'([^\']+)\'\s*[,)]/',
                $source,
                $matches,
            );
            // ⚠️ La clé n'est pas toujours un littéral SEUL. Dès qu'une route bascule un statut,
            // la forme courante est `addFlash('success', $actif ? 'x.activated' : 'x.deactivated')`
            // — et un motif qui exige `', 'clé')` n'en capture aucune des deux. C'était le cas de
            // `UserController::toggle()` et `runBulk()` : quatre routes, deux clés absentes du
            // catalogue, suite verte et clé brute dans le toast (vu le 2026-08-24).
            //
            // On capture donc l'ARGUMENT entier, puis toutes les chaînes qu'il contient : deux pour
            // un ternaire, une pour un littéral. Le motif de clé (`a.b_c`) écarte ce qui n'en est
            // pas — une concaténation dynamique reste, elle, hors de portée d'une analyse statique.
            preg_match_all('/\$this->addFlash\(\s*\'[^\']+\'\s*,\s*(.+?)\);/', $source, $nativeMatches);

            $nativeKeys = [];
            foreach ($nativeMatches[1] as $argument) {
                preg_match_all('/\'([a-z][a-z0-9_]*(?:\.[a-z0-9_]+)+)\'/', $argument, $literals);
                $nativeKeys = array_merge($nativeKeys, $literals[1]);
            }

            $found = array_merge($matches[1], $nativeKeys);
            if ([] !== $found) {
                $keys[$file->getRelativePathname()] = array_values(array_unique($found));
            }
        }

        return $keys;
    }
}
