<?php

namespace App\Services;

use Illuminate\Support\LazyCollection;

class UserService
{
    private $providers;

    public function __construct(array $providers = [])
    {
        $this->providers = $providers;
    }

    public function getUsers(array $filters = []): LazyCollection
    {
        $providerName = $filters['provider'] ?? null;
        unset($filters['provider']);
        
        return LazyCollection::make(function () use ($filters, $providerName) {
            foreach ($this->providers as $provider) {
                if (!$providerName || $provider->getName() === $providerName) {
                    foreach ($provider->getUsers($filters) as $user) {
                        yield $user;
                    }
                }
            }
        });
    }
}
