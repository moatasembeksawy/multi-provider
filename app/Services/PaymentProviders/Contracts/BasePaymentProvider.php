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
    protected array $statusMap = [];
    protected string $filePath;
    protected array $fieldMap = [];

    protected function readJsonFile(): LazyCollection
    {
        $filePath = Storage::path($this->filePath);

        return LazyCollection::make(function () use ($filePath) {
            $parser = new JsonParser($filePath);
            yield from $parser->parse($filePath);
        });
    }

    protected function normalizeDate(string $date): string
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    protected function transformUser(array $user): array
    {
        $transformed = [];
        foreach ($this->fieldMap as $originalField => $normalizedField) {
            if (isset($user[$originalField])) {
                $transformed[$normalizedField] = $user[$originalField];
            }else{
                $transformed[$originalField] = $user[$normalizedField];
            }
        }

        $transformed['provider'] = $this->getName();
        $transformed['status'] = $this->transformStatus($user[$this->fieldMap['status']]);
        $transformed['created_at'] = $this->normalizeDate($user[$this->fieldMap['created_at']]);
        
        return $transformed;
    }

    public function transformStatus(string|int $status): string
    {
        return $this->statusMap[$status] ?? 'unknown';
    }

    public function getUsers(array $filters = []): LazyCollection
    {
        return $this->readJsonFile()
            ->map(fn($user) => $this->transformUser($user))
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
