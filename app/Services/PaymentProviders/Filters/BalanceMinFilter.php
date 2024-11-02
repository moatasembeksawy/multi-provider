<?php

namespace App\Services\PaymentProviders\Filters;

use App\Services\PaymentProviders\Contracts\BaseFilter;

class BalanceMinFilter extends BaseFilter
{
    public function handle($user, \Closure $next)
    {
        if ($this->shouldSkip()) {
            return $next($user);
        }

        $balance = $this->getValue($user, 'balance');
        return $balance >= $this->value ? $next($user) : null;
    }
}
