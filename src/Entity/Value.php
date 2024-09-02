<?php

namespace App\Entity;

use App\Repository\ValueRepository;
use App\ValueObject\AppUuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValueRepository::class)]
class Value
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    private AppUuid $uuid;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\Column(length: 255)]
    private ?string $formulaire = null;

    #[ORM\Column(length: 255)]
    private ?string $zone = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'values')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Declaration $declaration = null;

    public function __construct()
    {
        $this->uuid = AppUuid::generate();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?AppUuid
    {
        return $this->uuid;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getFormulaire(): ?string
    {
        return $this->formulaire;
    }

    public function setFormulaire(string $formulaire): static
    {
        $this->formulaire = $formulaire;

        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): static
    {
        $this->zone = $zone;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDeclaration(): ?Declaration
    {
        return $this->declaration;
    }

    public function setDeclaration(?Declaration $declaration): static
    {
        $this->declaration = $declaration;

        return $this;
    }
}
