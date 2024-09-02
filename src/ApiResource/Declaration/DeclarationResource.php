<?php

namespace App\ApiResource\Declaration;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Declaration\State\Processor\CreateDeclarationProcessor;
use App\ApiResource\Declaration\State\Provider\GetDeclarationProvider;
use App\Entity\Declaration;

#[ApiResource(shortName: 'declaration')]
#[Get(
    provider: GetDeclarationProvider::class
)]
#[Post(
    processor: CreateDeclarationProcessor::class
)]
class DeclarationResource
{
    public function __construct(
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