<?php

namespace StallionExpress\AuthUtility\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use StallionExpress\AuthUtility\Trait\TimezoneTrait;
use Illuminate\Support\Facades\Auth;

class SetUserTimezone
{
    use TimezoneTrait;
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\AuthUtility::isUserCustomerOrCustomerStaff(Auth::user())) {  
            $userId = \AuthUtility::getUserId(Auth::user(), null);
            $this->getAndSetUserTimezone($userId);
        }

        foreach ($request->all() as $key => $value) {
            if(in_array($key, ['three_pl_customer_id', 'customer_id', 'user_id']))
            {   
                $userId = \AuthUtility::getUserId(Auth::user(), $value);
                $this->getAndSetUserTimezone($userId);
            }
        }

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        $this->setDefaultTimezone();
    }
}
