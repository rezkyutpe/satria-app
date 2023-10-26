<?php

namespace App\Http\Middleware;


use Illuminate\Routing\Middleware\ThrottleRequests as 
BaseThrottleRequests;

class ThrottleRequests extends BaseThrottleRequests
{
    protected function buildResponse($key, $maxAttempts)
    {
        $retryAfter = $this->limiter->availableIn($key); 
        return redirect()->back()->withInput()->with('err_message','Too Many Attempts. Please try after '.$retryAfter .' seconds');
    
    }
}
