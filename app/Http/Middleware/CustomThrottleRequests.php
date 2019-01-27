<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests;
use App\Traits\ApiResponser;

class CustomThrottleRequests extends ThrottleRequests
{
    use ApiResponser;

    protected function buildException($key, $maxAttempts)
    {
        $retryAfter = $this->getTimeUntilNextRetry($key);

        $headers = $this->getHeaders(
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );

        return new ThrottleRequestsException(
            'Too Many Attempts.', null, $headers
        );

        // return $response = $this->errorResponse('Too many attempts', 429);
    }
}
