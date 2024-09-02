<?php

namespace App\ApiResource\Declaration\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Declaration\DeclarationResource;

class GetDeclarationProvider implements ProviderInterface
{

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): DeclarationResource
    {
        return new DeclarationResource();
    }
}