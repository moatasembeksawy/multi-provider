<?php

namespace App\Services\PaymentProviders\Filters;

use App\Services\PaymentProviders\Contracts\BaseFilter;

class CurrencyFilter extends BaseFilter
{
    public function handle($user, \Closure $next)
    {
        if ($this->shouldSkip()) {
            return $next($user);
        }

        $currency = $this->getValue($user, 'currency');

        return strtoupper($currency) === strtoupper($this->value) ? $next($user) : null;
    }
}
