<?php

namespace App\Services\PaymentProviders;

use App\Services\PaymentProviders\Contracts\BasePaymentProvider;

class DataProviderX extends BasePaymentProvider
{    
    protected string $filePath = 'providers/DataProviderX.json';

    protected array $fieldMap = [
        'balance' => 'parentAmount',
        'currency' => 'Currency',
        'email' => 'parentEmail',
        'status' => 'statusCode',
        'created_at' => 'registerationDate',
        'id' => 'parentIdentification',
    ];


    protected array $statusMap = [
        1 => 'authorised',
        2 => 'decline',
        3 => 'refunded'
    ];

    public function getName(): string
    {
        return 'DataProviderX';
    }
}
