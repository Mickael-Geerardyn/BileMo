<?php

namespace App\Entity;

use App\Repository\MobileRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MobileRepository::class)]
class Mobile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Groups("getMobile")]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
	#[Groups("getMobile")]
	#[Assert\NotBlank(message: "Le nom du smartphone est obligatoire")]
	#[Assert\Length(max: 100, maxMessage: "Le nom ne peut comporter plus de {{ limit }} caractères")]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
	#[Groups("getMobile")]
	#[Assert\NotBlank(message: "La description du smartphone est obligatoire")]
    private ?string $description = null;

    #[ORM\Column]
	#[Groups("getMobile")]
    private ?int $quantity = null;

    #[ORM\Column]
	#[Groups("getMobile")]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
	#[Groups("getMobile")]
    private ?DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(cascade: ["persist"], inversedBy: 'mobile')]
    #[ORM\JoinColumn(nullable: false)]
	#[Groups("getMobile")]
	#[Assert\NotBlank(message: "La marque du smartphone doit être renseignée")]
    private ?Brand $brand = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at->format("d-m-Y");
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

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }
}
