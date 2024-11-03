<?php

namespace App\Services\PaymentProviders;

use Carbon\Carbon;
use App\Services\PaymentProviders\Contracts\BasePaymentProvider;

class DataProviderY extends BasePaymentProvider
{
    protected string $filePath = 'providers/DataProviderY.json';

    public function mapData(array $data): array
    {
        return [
            'balance' => $data['balance'],
            'currency' => $data['currency'],
            'email' => $data['email'],
            'status' => $this->mapStatus($data['status']),
            'created_at' => Carbon::parse($data['created_at'])->format('Y-m-d'),
            'id' => $data['id'],
            'provider' => $this->getName(),
        ];
    }

    protected function mapStatus($status): string
    {
        return match($status) {
            100 => 'authorised',
            200 => 'decline',
            300 => 'refunded',
            default => 'unknown'
        };
    }

    public function getName(): string
    {
        return 'DataProviderY';
    }
}
