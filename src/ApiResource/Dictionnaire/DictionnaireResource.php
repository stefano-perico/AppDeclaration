<?php

namespace App\ApiResource\Dictionnaire;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\ApiResource\Declaration\State\Processor\CreateDeclarationProcessor;
use App\ApiResource\Dictionnaire\State\Provider\GetDictionnaireProvider;
use App\Entity\Declaration;

#[ApiResource(shortName: 'Dictionnaire')]
#[Get(
    provider: GetDictionnaireProvider::class,
)]
#[Post(
    processor: CreateDeclarationProcessor::class,
)]
class DictionnaireResource
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