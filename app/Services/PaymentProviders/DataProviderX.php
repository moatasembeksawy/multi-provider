<?php

namespace App\Services\PaymentProviders;

use Carbon\Carbon;
use App\Services\PaymentProviders\Contracts\BasePaymentProvider;

class DataProviderX extends BasePaymentProvider
{    
    protected string $filePath = 'providers/DataProviderX.json';

    public function mapData(array $data): array
    {
        return [
            'balance' => $data['parentAmount'],
            'currency' => $data['Currency'],
            'email' => $data['parentEmail'],
            'status' => $this->mapStatus($data['statusCode']),
            'created_at' => Carbon::parse($data['registerationDate'])->format('Y-m-d'),
            'id' => $data['parentIdentification'],
            'provider' => $this->getName(),
        ];
    }

    protected function mapStatus($status): string
    {
        return match($status) {
            1 => 'authorised',
            2 => 'decline',
            3 => 'refunded',
            default => 'unknown'
        };
    }

    public function getName(): string
    {
        return 'DataProviderX';
    }
}
