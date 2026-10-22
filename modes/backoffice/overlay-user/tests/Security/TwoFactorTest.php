<?php

declare(strict_types=1);

namespace App\Tests\Security;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use OTPHP\TOTP;
use PHPUnit\Framework\Attributes\CoversNothing;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use function random_int;
use function sprintf;
use function uniqid;

/**
 * La double authentification optionnelle (`admin.two_factor` d'admin-bundle) : PROPOSÉE à
 * chaque compte depuis son menu, jamais imposée. Un compte qui l'a activée donne son code après son
 * mot de passe ; un compte qui ne l'a pas activée se connecte comme avant.
 *
 * ⚠️ Une adresse, un compte et un secret neufs par test : le limiteur de connexion, celui des codes
 * et le cache anti-rejeu vivent sur le disque, partagés par les processus de paratest, et survivent
 * d'un passage à l'autre.
 */
#[CoversNothing]
final class TwoFactorTest extends WebTestCase
{
    private const string PASSWORD = 'correct-horse-battery';

    private KernelBrowser $client;

    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->disableReboot();
        $this->client->setServerParameter('REMOTE_ADDR', sprintf('2001:db8::%x:%x', random_int(0, 0xFFFF), random_int(0, 0xFFFF)));
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);
    }

    public function testTheAccountMenuLeadsToTheScreen(): void
    {
        $this->client->loginUser($this->user());
        $crawler = $this->client->request('GET', '/admin/account/two-factor');

        self::assertResponseIsSuccessful();
        self::assertCount(1, $crawler->filter('[data-totp-qr]'), 'Un compte sans second facteur se voit proposer le QR code.');
        self::assertGreaterThan(0, $crawler->filter('a[href="/admin/account/two-factor"]')->count(), 'Le menu de compte y mène.');
    }

    public function testAnAccountWithTheSecondFactorSignsInWithItsCodeAndNotWithoutIt(): void
    {
        $secret = static::getContainer()->get(TotpAuthenticatorInterface::class)->generateSecret();
        if ('' === $secret) {
            self::fail('scheb tire un secret non vide.');
        }

        $this->signIn($this->user($secret));
        // Le formulaire redirige vers sa cible habituelle ; c'est scheb qui, sur la page suivante,
        // demande le code tant qu'il manque.
        $this->client->followRedirect();
        self::assertResponseRedirects('/2fa', null, 'Le mot de passe seul n\'ouvre plus la session.');

        $crawler = $this->client->request('GET', '/2fa');
        $this->client->submit($crawler->filter('form')->form(['_auth_code' => '000000']));
        $crawler = $this->client->followRedirect();
        self::assertCount(1, $crawler->filter('[data-totp-error]'), 'Un code faux est refusé, la page le dit.');

        $this->client->submit($crawler->filter('form')->form(['_auth_code' => TOTP::createFromSecret($secret)->now()]));
        self::assertResponseRedirects();
        self::assertStringNotContainsString('/2fa', (string) $this->client->getResponse()->headers->get('Location'));
        self::assertStringNotContainsString('/login', (string) $this->client->getResponse()->headers->get('Location'));
    }

    /**
     * La page du code garde son choix de langue. Pendant la saisie, scheb renvoie vers `/2fa` toute
     * requête qui n'est ni publique ni permise à un compte à moitié connecté ; `/locale`, sans règle
     * d'accès, en était : cliquer « FR » ramenait la même page, en anglais.
     */
    public function testTheCodePageSwitchesLanguage(): void
    {
        $secret = static::getContainer()->get(TotpAuthenticatorInterface::class)->generateSecret();
        if ('' === $secret) {
            self::fail('scheb tire un secret non vide.');
        }

        $this->signIn($this->user($secret));
        $crawler = $this->client->request('GET', '/2fa');
        $token = (string) $crawler->filter('[data-ui--locale-switcher-token-value]')->attr('data-ui--locale-switcher-token-value');

        $this->client->request('POST', '/locale', ['locale' => 'fr', '_token' => $token]);
        self::assertResponseStatusCodeSame(204, 'Le changement de langue passe pendant la saisie du code.');

        $crawler = $this->client->request('GET', '/2fa');
        self::assertSame('fr', $crawler->filter('html')->attr('lang'));
    }

    /**
     * Les codes de secours : dix, tirés à l'activation et montrés sur la page qui suit —
     * elle seule ; ensuite l'écran les compte.
     */
    public function testActivationShowsTenBackupCodesOnce(): void
    {
        $user = $this->user();
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', '/admin/account/two-factor');
        $secret = $user->getTotpAuthenticationConfiguration()?->getSecret();
        if (null === $secret || '' === $secret) {
            self::fail('L\'écran pose un secret en attente.');
        }

        $this->client->submit($crawler->filter('form[name=two_factor_enable]')->form(['two_factor_enable[code]' => TOTP::createFromSecret($secret)->now()]));
        $crawler = $this->client->followRedirect();

        self::assertCount(10, $crawler->filter('[data-new-backup-codes] li'));
        self::assertMatchesRegularExpression('/^[A-Z2-9]{5}-[A-Z2-9]{5}$/', trim($crawler->filter('[data-new-backup-codes] li')->first()->text()));

        $crawler = $this->client->request('GET', '/admin/account/two-factor');
        self::assertCount(0, $crawler->filter('[data-new-backup-codes]'), 'Une seule fois : recharger ne les remontre pas.');
        self::assertSame('10', $crawler->filter('[data-backup-codes-left]')->attr('data-backup-codes-left'));
    }

    /**
     * ⚠️ Le téléphone perdu : un code de secours ouvre la session à la place du code — une seule fois.
     */
    public function testABackupCodeSignsInOnceInsteadOfTheApp(): void
    {
        $secret = static::getContainer()->get(TotpAuthenticatorInterface::class)->generateSecret();
        if ('' === $secret) {
            self::fail('scheb tire un secret non vide.');
        }
        $user = $this->user($secret);
        $user->replaceBackupCodes(['K7QM2-XW9RT']);
        $this->entityManager->flush();

        foreach ([true, false] as $accepted) {
            $this->signIn($user);
            $crawler = $this->client->request('GET', '/2fa');
            $this->client->submit($crawler->filter('[data-totp-backup] form')->form(['_auth_code' => 'k7qm2-xw9rt']));
            $location = (string) $this->client->getResponse()->headers->get('Location');

            if ($accepted) {
                self::assertStringNotContainsString('/2fa', $location, 'Le code de secours ouvre la session.');
                $this->client->request('GET', '/logout');
            } else {
                self::assertStringContainsString('/2fa', $location, 'Déjà servi, il est refusé.');
            }
        }

        $this->entityManager->clear();
        self::assertSame(0, $this->entityManager->find(User::class, $user->getId())?->countBackupCodes());
    }

    public function testAnAccountWithoutItSignsInAsBefore(): void
    {
        $this->signIn($this->user());
        self::assertResponseRedirects();
        self::assertStringNotContainsString('/login', (string) $this->client->getResponse()->headers->get('Location'));

        $this->client->followRedirect();
        self::assertStringNotContainsString('/2fa', (string) $this->client->getResponse()->headers->get('Location'));
        self::assertStringNotContainsString('/2fa', $this->client->getRequest()->getUri());
    }

    private function signIn(User $user): void
    {
        $crawler = $this->client->request('GET', '/login');
        $this->client->submit($crawler->filter('form')->form(['_username' => $user->getEmail(), '_password' => self::PASSWORD]));
    }

    private function user(?string $secret = null): User
    {
        $user = new User()
            ->setEmail(sprintf('%s@example.test', uniqid('two-factor', false)))
            ->setFirstName('Ada')
            ->setLastName('Lovelace')
            ->setIsActive(true);
        $user->setPassword(static::getContainer()->get(UserPasswordHasherInterface::class)->hashPassword($user, self::PASSWORD));
        if (null !== $secret) {
            $user->startTotpEnrolment($secret)->confirmTotp(new DateTimeImmutable());
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
