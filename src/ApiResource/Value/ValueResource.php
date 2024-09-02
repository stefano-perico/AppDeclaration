<?php

namespace App\ApiResource\Value;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Declaration;

#[ApiResource(shortName: 'Value')]
class ValueResource
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