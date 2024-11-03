<?php

namespace App\Services\PaymentProviders\Contracts;

use Illuminate\Support\Carbon;
use Illuminate\Pipeline\Pipeline;
use Cerbero\JsonParser\JsonParser;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\Storage;
use App\Services\PaymentProviders\Filters\StatusFilter;
use App\Services\PaymentProviders\Filters\CurrencyFilter;
use App\Services\PaymentProviders\Filters\BalanceMaxFilter;
use App\Services\PaymentProviders\Filters\BalanceMinFilter;



abstract class BasePaymentProvider implements PaymentProviderInterface
{
    protected string $filePath;

    protected function readJsonFile(): LazyCollection
    {
        $filePath = Storage::path($this->filePath);

        return LazyCollection::make(function () use ($filePath) {
            $parser = new JsonParser($filePath);
            yield from $parser->parse($filePath);
        });
    }

    public function getUsers(array $filters = []): LazyCollection
    {
        return $this->readJsonFile()
            ->map(fn($user) => $this->mapData($user))
            ->map(function ($user) use ($filters) {
                return app(Pipeline::class)
                    ->send($user)
                    ->through($this->buildFilters($filters))
                    ->thenReturn();
            })
            ->filter();
    }

    protected function buildFilters(array $filters): array
    {
        return array_filter([
            new BalanceMinFilter($filters['balanceMin'] ?? null),
            new BalanceMaxFilter($filters['balanceMax'] ?? null),
            new CurrencyFilter($filters['currency'] ?? null),
            new StatusFilter($filters['status'] ?? null),
        ]);
    }
}
