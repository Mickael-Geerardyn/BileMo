<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Groups(["getClientUsers", "getClientUser"])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true, nullable: false)]
	#[Assert\NotBlank(message: "L'email est obligatoire")]
	#[Assert\Length(max: 180, maxMessage: "L'email ne peut comporter plus de {{ limit }} caractères")]
	#[Groups(["getClientUsers", "getClientUser"])]
    private ?string $email = null;

    #[ORM\Column]
	#[Groups(["getClientUsers", "getClientUser"])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: false)]
	#[Assert\NotBlank(message: "Le mot de passe est obligatoire")]
    private string $password;

    #[ORM\Column]
	#[Groups(["getClientUsers", "getClientUser"])]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
	#[Groups(["getClientUsers", "getClientUser"])]
    private ?DateTimeImmutable $updated_at = null;

	#[ORM\ManyToOne(inversedBy: 'user')]
	#[ORM\JoinColumn(nullable: false)]
	#[Assert\NotBlank(message: "L'utilisateur doit être rattaché à un client")]
	#[Groups(["getClientUsers", "getClientUser"])]
	private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

	/**
	 * Important to add this method: uses by Lexik_jwt_authentication for users authentications
	 *
	 * @return string
	 */
	public function getUsername(): string {

		return $this->getUserIdentifier();
	}

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(): static
    {
        $this->created_at = new DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
	{
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
