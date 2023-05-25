<?php

namespace StallionExpress\AuthUtility\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use StallionExpress\AuthUtility\Trait\STEncodeDecodeTrait;

class ReplaceRouteAndRequestHashValueMiddleware
{
    use STEncodeDecodeTrait;

    public $url;

    public $client_id;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        //echo '_st_'.base64_encode('1');
        $routesParameters = $this->getRouteParameters($request);

        //print_r($routesParameters);
        if ($routesParameters) {
            $this->replaceRouteParameters($request, $routesParameters);
        }
        //print_r(($request->all()));

        $this->replaceRequestValue($request);
        //print_r(($request->all()));

        return $next($request);
    }

    /**
     * Get route parameters
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    private function getRouteParameters(Request $request): array
    {
        $routesParameters = [];
        foreach ($request->route()->parameters() as $key => $value) {
            $routesParameters[$key] = $value;
        }

        return $routesParameters;
    }

    /**
     * Replace route parameters
     *
     * @param  Request  $request
     * @param  array  $routesParameters
     * @return void
     */
    private function replaceRouteParameters(Request $request, array $routesParameters): void
    {
        foreach ($routesParameters as $key => $value) {
            $request->route()->setParameter($key, $this->decodeHashValue($value));
        }
    }

    private function replaceRequestValue(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            $decodedValue = $this->decodeRequestValue($value);
            $request->request->set($key, $decodedValue);
        }
    }

    //create recursion function to decode all value in request
    private function decodeRequestValue($value)
    {
        if (is_string($value) && Str::contains($value, '_st_')) {
            $value = $this->decodeHashValue($value);
        } elseif (is_array($value)) {
            $value = array_map([$this, 'decodeRequestValue'], $value);
        }

        return $value;
    }
}
