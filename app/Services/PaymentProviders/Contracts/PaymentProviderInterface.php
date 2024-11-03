<?php

namespace App\Services\PaymentProviders\Contracts;

use Illuminate\Support\LazyCollection;

interface PaymentProviderInterface
{
    public function getName(): string;
    public function getUsers(array $filters = []): LazyCollection;
    public function mapData(array $record): array;
}
