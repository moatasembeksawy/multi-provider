<?php

namespace App\Services\PaymentProviders\Filters;

use App\Services\PaymentProviders\Contracts\BaseFilter;

class StatusFilter extends BaseFilter
{
    public function handle($user, \Closure $next)
    {
        if ($this->shouldSkip()) {
            return $next($user);
        }

        $status = $this->getValue($user, 'status');

        return $status === $this->value ? $next($user) : null;
    }
}
