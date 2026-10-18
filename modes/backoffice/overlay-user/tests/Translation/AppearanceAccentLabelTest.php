<?php

declare(strict_types=1);

namespace App\Tests\Translation;

use App\Security\BackofficeLocales;
use Jul6Art\AdminBundle\Appearance\AccentColor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use function sprintf;

/**
 * Chaque accent de la page Apparence a son libellé, dans chaque langue.
 *
 * Le gabarit d'admin-bundle lit `appearance.accent.<valeur>` pour chaque cas d'`AccentColor` ; le
 * bundle ne peut pas savoir si la clé existe ici. `brand` — l'accent par défaut, la couleur du
 * produit (admin-bundle ≥ 1.27) — en manquait : l'écran affichait la clé brute en tête de liste.
 */
final class AppearanceAccentLabelTest extends KernelTestCase
{
    public function testEveryAccentHasALabel(): void
    {
        self::bootKernel();

        $translator = static::getContainer()->get(TranslatorInterface::class);
        self::assertInstanceOf(TranslatorBagInterface::class, $translator);

        $missing = [];
        foreach (BackofficeLocales::SUPPORTED as $locale) {
            $catalogue = $translator->getCatalogue($locale);
            foreach (AccentColor::cases() as $accent) {
                // `defines()` et non `has()` : `has()` suit le repli d'une langue à l'autre.
                if (!$catalogue->defines('appearance.accent.'.$accent->value, 'appearance')) {
                    $missing[] = sprintf('[%s] appearance.accent.%s', $locale, $accent->value);
                }
            }
        }

        self::assertSame([], $missing, "Accents sans libellé :\n".implode("\n", $missing));
    }
}
