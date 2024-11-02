<?php

namespace App\Services\PaymentProviders;

use App\Services\PaymentProviders\Contracts\BasePaymentProvider;

class DataProviderY extends BasePaymentProvider
{
    protected string $filePath = 'providers/DataProviderY.json';

    protected array $fieldMap = [
        'balance' => 'balance',
        'currency' => 'currency',
        'email' => 'email',
        'status' => 'status',
        'created_at' => 'created_at',
        'id' => 'id'
    ];
   
    protected array $statusMap = [
        100 => 'authorised',
        200 => 'decline',
        300 => 'refunded'
    ];


    public function getName(): string
    {
        return 'DataProviderY';
    }
}
