<?php

namespace App\Entity;

use App\Repository\DictionnaireValueRepository;
use App\ValueObject\AppUuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DictionnaireValueRepository::class)]
class DictionnaireValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    private AppUuid $uuid;

    #[ORM\Column(length: 255)]
    private ?string $zone = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $donnee = null;

    #[ORM\Column]
    private ?bool $repetable = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $format = null;

    #[ORM\Column(length: 255)]
    private ?string $balise = null;

    #[ORM\Column(length: 255)]
    private ?string $intitule = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codeNREF = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codeLigne = null;

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

    public function getDonnee(): ?string
    {
        return $this->donnee;
    }

    public function setDonnee(string $donnee): static
    {
        $this->donnee = $donnee;

        return $this;
    }

    public function isRepetable(): ?bool
    {
        return $this->repetable;
    }

    public function setRepetable(bool $repetable): static
    {
        $this->repetable = $repetable;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(?string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getBalise(): ?string
    {
        return $this->balise;
    }

    public function setBalise(string $balise): static
    {
        $this->balise = $balise;

        return $this;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getCodeNREF(): ?string
    {
        return $this->codeNREF;
    }

    public function setCodeNREF(?string $codeNREF): static
    {
        $this->codeNREF = $codeNREF;

        return $this;
    }

    public function getCodeLigne(): ?string
    {
        return $this->codeLigne;
    }

    public function setCodeLigne(?string $codeLigne): static
    {
        $this->codeLigne = $codeLigne;

        return $this;
    }
}
