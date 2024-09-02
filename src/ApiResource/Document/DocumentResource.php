<?php

namespace App\ApiResource\Document;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Document\State\Processor\CreateDocumentProcessor;
use App\ApiResource\Document\State\Provider\GetDocumentProvider;
use App\Entity\Declaration;

#[ApiResource(shortName: 'Document')]
#[Get(
    provider: GetDocumentProvider::class,
)]
#[Post(
    processor: CreateDocumentProcessor::class,
)]
class DocumentResource
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        public string $id,
    ) {
    }

    public static function fromModel(Declaration $declaration): static
    {
        return new self(
            id: $declaration->getUuid(),
        );
    }
}