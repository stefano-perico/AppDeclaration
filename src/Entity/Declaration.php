<?php

namespace App\Entity;

use App\Repository\DeclarationRepository;
use App\ValueObject\AppUuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeclarationRepository::class)]
class Declaration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'uuid')]
    private AppUuid $uuid;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $period = null;

    #[ORM\Column(length: 255)]
    private ?string $formulaire = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    private ?int $millesime = null;

    #[ORM\Column(length: 255)]
    private ?string $declarantSiren = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(nullable: true)]
    private ?array $errors = null;

    #[ORM\Column(nullable: true)]
    private ?array $rib = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $deletedAt = null;

    /**
     * @var Collection<int, Value>
     */
    #[ORM\OneToMany(targetEntity: Value::class, mappedBy: 'Declaration')]
    private Collection $values;

    /**
     * @var Collection<int, Document>
     */
    #[ORM\OneToMany(targetEntity: Document::class, mappedBy: 'declaration')]
    private Collection $documents;

    public function __construct()
    {
        $this->values = new ArrayCollection();
        $this->documents = new ArrayCollection();
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): static
    {
        $this->period = $period;

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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getMillesime(): ?int
    {
        return $this->millesime;
    }

    public function setMillesime(int $millesime): static
    {
        $this->millesime = $millesime;

        return $this;
    }

    public function getDeclarantSiren(): ?string
    {
        return $this->declarantSiren;
    }

    public function setDeclarantSiren(string $declarantSiren): static
    {
        $this->declarantSiren = $declarantSiren;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function setErrors(?array $errors): static
    {
        $this->errors = $errors;

        return $this;
    }

    public function getRib(): ?array
    {
        return $this->rib;
    }

    public function setRib(?array $rib): static
    {
        $this->rib = $rib;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return Collection<int, Value>
     */
    public function getValues(): Collection
    {
        return $this->values;
    }

    public function addValue(Value $value): static
    {
        if (!$this->values->contains($value)) {
            $this->values->add($value);
            $value->setDeclaration($this);
        }

        return $this;
    }

    public function removeValue(Value $value): static
    {
        if ($this->values->removeElement($value)) {
            // set the owning side to null (unless already changed)
            if ($value->getDeclaration() === $this) {
                $value->setDeclaration(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setDeclaration($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getDeclaration() === $this) {
                $document->setDeclaration(null);
            }
        }

        return $this;
    }
}
