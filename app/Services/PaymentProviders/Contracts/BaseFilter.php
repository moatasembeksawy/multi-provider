<?php

namespace App\Services\PaymentProviders\Contracts;

abstract class BaseFilter
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    abstract public function handle($user, \Closure $next);

    protected function shouldSkip(): bool
    {
        return $this->value === null;
    }

    protected function getValue($user, string $key)
    {
        return is_array($user) ? ($user[$key] ?? null) : null;
    }
}