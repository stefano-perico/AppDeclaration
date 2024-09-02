<?php

namespace App\ApiResource\Document\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Document\DocumentResource;

class GetDocumentProvider implements ProviderInterface
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): DocumentResource
    {
        // TODO: Implement provide() method.
    }
}