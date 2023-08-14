<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Groups(["getMobiles", "getMobile"])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
	#[Groups(["getMobiles", "getMobile"])]
	#[Assert\NotBlank(message: "Le nom de la marque est obligatoire")]
    private ?string $name = null;

    #[ORM\Column]
	#[Groups(["getMobiles", "getMobile"])]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
	#[Groups(["getMobiles", "getMobile"])]
    private ?DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Mobile::class, orphanRemoval: true)]
    private Collection $mobile;

    public function __construct()
    {
        $this->mobile = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Mobile>
     */
    public function getMobile(): Collection
    {
        return $this->mobile;
    }

    public function addMobile(Mobile $mobile): static
    {
        if (!$this->mobile->contains($mobile)) {
            $this->mobile->add($mobile);
            $mobile->setBrand($this);
        }

        return $this;
    }

    public function removeMobile(Mobile $mobile): static
    {
        if ($this->mobile->removeElement($mobile)) {
            // set the owning side to null (unless already changed)
            if ($mobile->getBrand() === $this) {
                $mobile->setBrand(null);
            }
        }

        return $this;
    }
}
