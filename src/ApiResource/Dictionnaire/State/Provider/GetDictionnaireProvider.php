<?php

namespace App\ApiResource\Dictionnaire\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\Dictionnaire\DictionnaireResource;

class GetDictionnaireProvider implements ProviderInterface
{

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): DictionnaireResource
    {
        // TODO: Implement provide() method.
    }
}