<?php

namespace App\Entity;

use App\Repository\DictionnaireRepository;
use App\ValueObject\AppUuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DictionnaireRepository::class)]
class Dictionnaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    private AppUuid $uuid;

    #[ORM\Column(length: 255)]
    private ?string $millesime = null;

    #[ORM\Column(length: 255)]
    private ?string $formulaire = null;

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

    public function getMillesime(): ?string
    {
        return $this->millesime;
    }

    public function setMillesime(string $millesime): static
    {
        $this->millesime = $millesime;

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
}
