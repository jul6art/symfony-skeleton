<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\ExactFilter;
use ApiPlatform\Doctrine\Orm\Filter\PartialSearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\QueryParameter;
use App\Repository\UserRepository;
use App\Security\BackofficeLocales;
use App\Security\PermissionCodes;
use App\Security\UserRoles;
use Doctrine\ORM\Mapping as ORM;
use Jul6Art\AclBundle\Contract\AclTenantInterface;
use Jul6Art\AclBundle\Contract\AclUserInterface;
use Jul6Art\AdminBundle\Appearance\ColorMode;
use Jul6Art\AdminBundle\Contract\AdminUserInterface;
use Jul6Art\AdminBundle\Contract\AppearanceAwareInterface;
use Jul6Art\AdminBundle\Contract\TwoFactorAwareInterface;
use Jul6Art\AdminBundle\Entity\Traits\AppearancePreferencesTrait;
use Jul6Art\AdminBundle\Entity\Traits\TwoFactorTrait;
use Jul6Art\ApiBundle\Filter\CaseInsensitiveOrderFilter;
use Jul6Art\ApiBundle\Filter\ConcatOrderFilter;
use Jul6Art\ApiBundle\Filter\OrSearchFilter;
use Jul6Art\CoreBundle\Entity\Traits\IdTrait;
use Jul6Art\CoreBundle\Entity\Traits\SoftDeletableTrait;
use Jul6Art\CoreBundle\Entity\Traits\TimestampableTrait;
use Jul6Art\CoreBundle\Util\Strings;
use Jul6Art\PushBundle\Attribute\BroadcastableEntity;
use Override;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\TypeInfo\Type\BuiltinType;
use Symfony\Component\TypeInfo\TypeIdentifier;
use Symfony\Component\Validator\Constraints as Assert;

use function in_array;

/**
 * Le compte.
 *
 * Il implémente cinq contrats, tous étroits, et c'est ce qui permet à quatre bundles de s'en
 * servir sans qu'aucun ne l'impose :
 *
 * - `UserInterface` — Symfony ;
 * - `AclUserInterface` — le moteur de permissions. `getTenant()` rend `null` : cette application
 *   n'a pas de locataires, et le contrat l'a toujours prévu ;
 * - `AdminUserInterface` — ce que la coquille affiche : un nom, deux initiales, une photo ;
 * - `AppearanceAwareInterface` — les préférences d'affichage, dont cinq colonnes viennent du trait ;
 * - `TwoFactorAwareInterface` — la double authentification optionnelle, dont les deux colonnes
 *   viennent elles aussi d'un trait.
 *
 * ⚠️ `getColorMode()` / `setColorMode()` ne sont PAS dans le trait, à dessein : presque toute
 * application a déjà une colonne pour ça sous son propre nom. Ces deux méthodes sont le
 * branchement, pas une redondance.
 *
 * L'exposition en API sert la datatable : elle lit `GET /api/users`, avec la pagination, le tri et
 * les filtres d'API Platform. Le mot de passe n'appartient à aucun groupe de sérialisation, donc
 * il ne peut pas fuir.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'user.email.unique')]
#[ORM\Table(name: '`user`')]
#[ORM\HasLifecycleCallbacks]
#[BroadcastableEntity]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('".PermissionCodes::USER_READ."')"),
        new Get(security: "is_granted('".PermissionCodes::USER_READ."')"),
    ],
    normalizationContext: ['groups' => ['user:read']],
    order: ['id' => 'DESC'],
)]
// ⚠️ `#[QueryParameter]` et non `#[ApiFilter]` : déprécié par API Platform 4.4, et les filtres de
// `jul6art/api-bundle` 2.x ne se déclarent plus que par paramètre — un `#[ApiFilter(OrSearchFilter…)]`
// empêche le conteneur de compiler (« does not have argument $properties »).
#[QueryParameter(key: 'email', filter: new PartialSearchFilter(caseSensitive: true), property: 'email')]
#[QueryParameter(key: 'isActive', filter: new ExactFilter(), property: 'isActive', nativeType: new BuiltinType(TypeIdentifier::BOOL), castToNativeType: true)]
#[QueryParameter(key: 'roles', filter: new PartialSearchFilter(caseSensitive: true), property: 'roles')]
#[QueryParameter(key: 'search', filter: new OrSearchFilter(), properties: ['email', 'firstName', 'lastName'])]
#[QueryParameter(key: 'order[:property]', filter: new CaseInsensitiveOrderFilter(), properties: ['id', 'email', 'firstName', 'lastName', 'createdAt'])]
// ⚠️ `fullName` n'est PAS une colonne : c'est un getter qui concatène prénom et nom. Un
// `OrderFilter` ordinaire écrirait `ORDER BY u.fullName` — champ inconnu, erreur DQL. Le
// `ConcatOrderFilter` d'`api-bundle` existe pour ce cas exact et ordonne sur les deux colonnes
// réelles. Sans lui, la colonne PRINCIPALE de l'écran des comptes promet un tri que le serveur
// jette en silence, et `tests/DataTable/SortFieldMappingTest.php` le refuse — c'est le filtre
// qu'il attend, pas un passage de la colonne en lecture seule.
#[QueryParameter(key: 'order[fullName]', filter: new ConcatOrderFilter(['firstName', 'lastName']))]
// ⚠️ Sans ce filtre, `createdAt[after]` et `createdAt[before]` sont IGNORÉS : API Platform jette
// en silence un paramètre qu'aucun filtre ne réclame. La barre de filtres s'ouvrait, se
// remplissait, affichait « depuis le … » et ne retirait AUCUNE ligne — 103 avant, 103 après un
// `before=2020-01-01` (mesuré contre l'API le 2026-08-24). `OrderFilter` ne remplace rien : il
// trie, il ne filtre pas. Toute colonne date déclarant un `dateRangeFilter()` côté provider a son
// entrée ici, et `DateRangeFilterMappingTest` fait échouer la gate si les deux divergent.
#[QueryParameter(key: 'createdAt', filter: new DateFilter(), property: 'createdAt')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, AclUserInterface, AdminUserInterface, AppearanceAwareInterface, TwoFactorAwareInterface
{
    /**
     * Les cinq colonnes `appearance_*`, livrées par `admin-bundle`. Elles ne sont volontairement
     * exposées par aucun groupe de sérialisation : ce sont des préférences, pas des données.
     */
    use AppearancePreferencesTrait;
    use IdTrait;
    use SoftDeletableTrait;

    use TimestampableTrait;

    /**
     * La double authentification optionnelle (`admin.two_factor`) : les colonnes `totp_secret` et
     * `totp_enabled_at`. Aucun groupe de sérialisation : le secret se relit pour vérifier un code, il
     * ne se hache pas, et ne sort jamais de l'application.
     */
    use TwoFactorTrait;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 180)]
    #[Groups(['user:read'])]
    private string $email = '';

    /**
     * @var list<string>
     */
    #[ORM\Column]
    #[Groups(['user:read'])]
    private array $roles = [];

    #[ORM\Column]
    private string $password = '';

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    #[Groups(['user:read'])]
    private string $firstName = '';

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    #[Groups(['user:read'])]
    private string $lastName = '';

    // ⚠️ Pas de `#[Groups]` ICI : pour une propriété nommée `isActive`, Symfony cherche
    // `getIsActive()`. Le getter s'appelle `isActive()`, qui décrit une propriété « active » —
    // résultat, le champ n'était sérialisé sous AUCUN des deux noms. Le groupe est donc porté par
    // le getter, avec le nom que la datatable lit (`row.isActive`).
    #[ORM\Column(options: ['default' => true])]
    private bool $isActive = true;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['user:read'])]
    private ?string $avatarPath = null;

    /** Le mode de couleur. `admin-bundle` le laisse hors de son trait pour cette raison précise. */
    #[ORM\Column(length: 10, options: ['default' => 'light'])]
    private string $theme = ColorMode::Light->value;

    /**
     * La langue de l'interface.
     *
     * Pas de `#[Groups]` : c'est une préférence, comme les cinq colonnes `appearance_*`, et la
     * table des comptes n'en a pas besoin. La contrainte `Choice` va de pair avec la colonne non
     * nullable — le formulaire est la seule porte avant persistance.
     */
    #[ORM\Column(length: 5, options: ['default' => BackofficeLocales::DEFAULT])]
    #[Assert\Choice(choices: BackofficeLocales::SUPPORTED)]
    private string $locale = BackofficeLocales::DEFAULT;

    /** Transitoire : le formulaire l'écrit, le contrôleur le hache. Jamais mappé. */
    private ?string $plainPassword = null;

    public function __toString(): string
    {
        return $this->getDisplayName();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        // Normalisation à l'écriture : une adresse est insensible à la casse, la colonne UNIQUE ne
        // l'est pas. Sans ça, `Ada@x.test` et `ada@x.test` sont deux comptes.
        $this->email = Strings::lowerEmail($email) ?? '';

        return $this;
    }

    #[Override]
    public function getUserIdentifier(): string
    {
        // `UserInterface` promet une chaîne non vide ; la propriété, elle, a un défaut vide pour
        // qu'un `new User()` soit constructible dans un test ou une fixture.
        return '' === $this->email ? 'anonymous' : $this->email;
    }

    /**
     * @return list<string>
     */
    #[Override]
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = UserRoles::ROLE_USER;

        return array_values(array_unique($roles));
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = array_values(array_filter($roles, static fn (string $r): bool => UserRoles::ROLE_USER !== $r));

        return $this;
    }

    #[Override]
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * `UserInterface::eraseCredentials()` a quitté l'interface en Symfony 7.3 — d'où l'absence de
     * `#[\Override]`, qui serait une erreur fatale au chargement de la classe.
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /** Exposé en API : c'est la colonne que la datatable affiche et sur laquelle elle trie. */
    #[Groups(['user:read'])]
    public function getFullName(): string
    {
        return trim($this->firstName.' '.$this->lastName);
    }

    #[Override]
    #[Groups(['user:read'])]
    #[SerializedName('isActive')]
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * Le pendant de `deletedAt`, lu par les `condition` des actions de la datatable.
     *
     * ⚠️ Les deux champs ne se remplacent pas : `deletedAt` teinte la ligne supprimée,
     * `isDeleted` décide des actions offertes. Et une `condition` qui lit un champ ABSENT de la
     * réponse vaut `undefined` : `row.isDeleted` est faux pour tout le monde (« Restaurer »
     * n'apparaît JAMAIS) et `!row.isDeleted` vrai pour tout le monde (« Supprimer » s'offre sur
     * une ligne morte). Aucun des deux cas ne lève.
     *
     * ⚠️ Un getter, et pas un `#[Groups]` sur la propriété du trait : `isDeleted()` vient du
     * `SoftDeletableTrait`, et un attribut posé sur une propriété de trait porterait l'union des
     * groupes de tous ses consommateurs. Le nom de méthode diffère du nom sérialisé, d'où
     * `#[SerializedName]`.
     *
     * Défaut trouvé par `tests/Entity/SoftDeletableExposureTest.php` sur cegeta le 2026-09-02 :
     * le mode livrait le test sans le getter qu'il exige.
     */
    #[Groups(['user:read'])]
    #[SerializedName('isDeleted')]
    public function isDeletedForApi(): bool
    {
        return $this->isDeleted();
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    #[Override]
    public function isSuperAdmin(): bool
    {
        return in_array(UserRoles::ROLE_SUPER_ADMIN, $this->getRoles(), true);
    }

    /**
     * Pas de locataires dans cette application. Le contrat le prévoit depuis le premier jour, et
     * `acl.multi_tenant: false` dit au moteur de ne pas exiger ce qui n'existe pas.
     */
    #[Override]
    public function getTenant(): ?AclTenantInterface
    {
        return null;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    #[Override]
    public function getColorMode(): ColorMode
    {
        return ColorMode::fromStorage($this->theme);
    }

    #[Override]
    public function setColorMode(ColorMode $mode): static
    {
        $this->theme = $mode->value;

        return $this;
    }

    #[Override]
    public function getDisplayName(): string
    {
        return '' !== $this->getFullName() ? $this->getFullName() : $this->email;
    }

    #[Override]
    public function getInitials(): string
    {
        $initials = mb_substr($this->firstName, 0, 1).mb_substr($this->lastName, 0, 1);

        return '' !== $initials ? mb_strtoupper($initials) : mb_strtoupper(mb_substr($this->email, 0, 1));
    }

    #[Override]
    public function getAvatarPath(): ?string
    {
        return $this->avatarPath;
    }

    public function setAvatarPath(?string $avatarPath): static
    {
        $this->avatarPath = $avatarPath;

        return $this;
    }
}
