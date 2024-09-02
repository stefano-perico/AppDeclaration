<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use App\ValueObject\AppUuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    private AppUuid $uuid;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

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
