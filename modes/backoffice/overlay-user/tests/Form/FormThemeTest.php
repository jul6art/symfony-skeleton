<?php

declare(strict_types=1);

namespace App\Tests\Form;

use App\Acl\DoctrinePermissionStore;
use App\Entity\User;
use App\Security\DefaultRolePermissions;
use App\Security\UserRoles;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use function sprintf;

/**
 * Le thème de formulaire du projet, vérifié en RENDANT les écrans.
 *
 * ⚠️ Ce test existe à cause d'un défaut qu'aucune inspection de configuration n'attrape : les
 * `Custom*Type` d'`ui-bundle` rendent par `input_group_addon_widget`, qui écrit son PROPRE
 * `<input>` et ne délègue jamais à `form_widget_simple`. Une classe posée dans ce bloc-là
 * n'atteint donc pas le champ : l'`<input>` sort sans aucun attribut `class`, donc sans bordure
 * ni fond — blanc sur blanc, invisible, et le HTML reste parfaitement valide.
 *
 * La classe doit passer par les OPTIONS de `form_widget`, jamais par `form_widget_simple`.
 * Constaté à l'écran le 2026-08-23 sur quatre formulaires (rapport `docs/corrections/`).
 */
#[CoversNothing]
final class FormThemeTest extends WebTestCase
{
    /**
     * Le champ e-mail de « mot de passe oublié » : un `CustomEmailType` nu, sans rien autour pour
     * masquer le défaut. C'est le cas le plus dépouillé, donc le plus probant.
     */
    public function testTheForgottenPasswordEmailFieldCarriesTheControlClass(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/reset-password');

        self::assertResponseIsSuccessful();
        self::assertSame(
            1,
            $crawler->filter('input[type="email"].form-control')->count(),
            'Le champ e-mail doit porter `form-control` : sans elle il est blanc sur blanc.',
        );
    }

    public function testTheRegistrationFormCarriesTheControlClassOnEveryField(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        self::assertResponseIsSuccessful();
        self::assertSame(1, $crawler->filter('input[type="email"].form-control')->count());
        self::assertSame(
            2,
            $crawler->filter('input[type="password"].form-control')->count(),
            'Les deux bornes du RepeatedType sont des champs comme les autres.',
        );

        // Le conteneur compound du formulaire RACINE porte l'espacement : `form_widget(form)` rend
        // un `<div id="…">` intermédiaire, et un `space-y-*` posé sur le `<form>` ne descend pas
        // jusqu'aux lignes — il ne voit que ce div et le bouton.
        self::assertSame(
            1,
            $crawler->filter('form div#registration_form.form-grid')->count(),
            'Sans `form-grid` sur le conteneur, les lignes du formulaire sont collées.',
        );

        // Les deux bornes d'un RepeatedType sont dans le conteneur prévu pour elles, et non à plat.
        self::assertSame(1, $crawler->filter('.password-repeat-grid')->count());
    }

    /**
     * Un champ à add-on réserve la place de son icône, et l'icône se colore.
     *
     * ⚠️ Les deux défauts que ce test fige ont vécu ensemble sur les trois écrans
     * d'authentification (2026-08-24). L'add-on est posé en `absolute`, donc il RECOUVRE le champ :
     * sans gouttière, le texte saisi passe dessous — 8 px de chevauchement mesurés. Et il se
     * colorait en `text-gray-400`, une palette qu'aucun fichier scanné par Tailwind n'utilise :
     * la classe était purgée, l'icône héritait de la couleur du texte, NOIRE en clair.
     *
     * Le second n'est vérifiable ici que parce que le balisage vient du thème ; que la classe soit
     * réellement COMPILÉE dépend de `bundle-assets.js`, et cela se voit à l'écran.
     */
    public function testAFieldWithAnAddOnReservesItsGutterAndColoursIt(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        self::assertResponseIsSuccessful();

        self::assertSame(
            1,
            $crawler->filter('input[type="email"].form-control.pr-10')->count(),
            'Le champ e-mail doit réserver la place de son enveloppe, sinon la saisie passe dessous.',
        );
        self::assertSame(
            2,
            $crawler->filter('input[type="password"].form-control.pr-10')->count(),
            'Idem pour les deux bornes du mot de passe et leur œil.',
        );

        $html = (string) $client->getResponse()->getContent();
        self::assertStringContainsString('text-slate-400', $html, 'Les add-ons se colorent dans le vocabulaire de la coquille.');
        self::assertStringNotContainsString('text-gray-', $html, 'La palette `gray` n\'existe dans aucune feuille de ce projet.');
    }

    /**
     * Un champ de mot de passe prend `CustomPasswordType` d'`ui-bundle`, jamais le `PasswordType`
     * natif : c'est le FAIL A1 de `form-checklist.md`, « l'erreur la plus fréquente ». Le type
     * dédié apporte l'œil qui révèle la saisie — et le contrôleur `form--password` qui l'anime.
     */
    public function testEveryPasswordFieldCarriesTheRevealControl(): void
    {
        $client = static::createClient();

        foreach (['/register', '/login'] as $uri) {
            $crawler = $client->request('GET', $uri);
            self::assertGreaterThan(
                0,
                $crawler->filter('[data-controller="form--password"]')->count(),
                sprintf('« %s » : le champ mot de passe doit porter son contrôleur de révélation.', $uri),
            );
        }
    }

    /**
     * `row_attr` arrive sur le conteneur de la ligne, comme dans `form_div_layout` : un type qui
     * marque ses lignes (un `data-*` lu par un contrôleur Stimulus) perdait sa marque sans erreur.
     */
    public function testTheRowCarriesItsRowAttr(): void
    {
        self::bootKernel();
        // Sans jeton CSRF : hors requête, il n'y a pas de session où le ranger.
        $form = static::getContainer()->get('form.factory')->createNamedBuilder('demo', FormType::class, null, ['csrf_protection' => false])
            ->add('title', TextType::class, ['row_attr' => ['data-kind' => 'block', 'class' => 'extra']])
            ->add('active', CheckboxType::class, ['required' => false, 'row_attr' => ['data-kind' => 'toggle']])
            ->getForm();

        $html = static::getContainer()->get('twig')
            ->createTemplate('{{ form_row(form.title) }}{{ form_row(form.active) }}')
            ->render(['form' => $form->createView()]);
        $crawler = new Crawler($html);

        self::assertSame(1, $crawler->filter('div.form-field.extra[data-kind="block"]')->count());
        self::assertSame(1, $crawler->filter('div.form-field-checkbox[data-kind="toggle"]')->count());
    }

    /**
     * ⚠️ Le jeton CSRF ne doit PAS recevoir `form-control` : c'est la preuve que le thème trie ses
     * lignes au lieu de décorer tout ce qui passe.
     */
    public function testTheHiddenCsrfFieldIsNotDecorated(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        self::assertSame(0, $crawler->filter('input[type="hidden"].form-control')->count());
    }

    public function testTheAdminUserFormRendersRolesAsChoiceCardsAndNotSystemCheckboxes(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $this->createSchema();

        $client->loginUser($this->createUser(UserRoles::ROLE_SUPER_ADMIN));
        $crawler = $client->request('GET', '/admin/users/new');

        self::assertResponseIsSuccessful();

        self::assertSame(1, $crawler->filter('input[type="email"].form-control')->count(), 'CustomEmailType.');
        self::assertSame(1, $crawler->filter('input[type="password"].form-control')->count(), 'CustomPasswordType.');

        self::assertSame(
            1,
            $crawler->filter('.role-choice-grid')->count(),
            'Un ChoiceType `expanded` se rend en grille de cartes — les jetons existent dans admin-bundle.',
        );
        self::assertSame(
            2,
            $crawler->filter('input.role-choice-input')->count(),
            'Une entrée par rôle assignable.',
        );

        // L'interrupteur du compte actif reste ce qu'il est : le thème ne le casse pas au passage.
        self::assertSame(1, $crawler->filter('input.toggle-switch-input')->count());
    }

    /** Un champ en erreur porte `form-control-error` — la classe existe et n'était jamais posée. */
    public function testAnInvalidFieldCarriesTheErrorClass(): void
    {
        $client = static::createClient();
        $client->disableReboot();
        $this->createSchema();

        $crawler = $client->request('GET', '/reset-password');
        $form = $crawler->filter('form')->form(['reset_password_request_form[email]' => 'pas-une-adresse']);
        $crawler = $client->submit($form);

        self::assertSame(
            1,
            $crawler->filter('input[type="email"].form-control-error')->count(),
            'Le champ refusé doit se voir.',
        );
    }

    private function createSchema(): void
    {
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $schemaTool = new SchemaTool($entityManager);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        $store = static::getContainer()->get(DoctrinePermissionStore::class);
        foreach (DefaultRolePermissions::map() as $role => $permissions) {
            foreach ($permissions as $permission) {
                $store->grantToRole($role, $permission, null);
            }
        }
        $entityManager->flush();
    }

    private function createUser(string $role): User
    {
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $user = new User()
            ->setEmail(sprintf('%s@example.test', uniqid('theme', false)))
            ->setFirstName('Ada')
            ->setLastName('Lovelace')
            ->setRoles([$role])
            ->setIsActive(true);
        $user->setPassword($hasher->hashPassword($user, 'correct-horse-battery'));

        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }
}
