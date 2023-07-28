<?php

namespace StallionExpress\AuthUtility\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use StallionExpress\AuthUtility\Trait\STEncodeDecodeTrait;

class StallionLogoutController extends Controller
{
    use STEncodeDecodeTrait;

    public $url;


    public function __construct()
    {
        $this->url = config('stallionauthutility.authservice.url');
    }

    
    public function logout(Request $request)
    {
        $bearer = $request->bearerToken();
        if ($bearer) {
            $response = Http::withToken($bearer)
                ->withHeaders([
                    'Accept' => 'application/json',
                ])->get(config('stallionauthutility.authservice.url') . '/api/logout');

            if ($response->successful()) {
                return response()->json([
                    'data' => true,
                    'meta' => [
                        'message' => 'You have been logged out successfully.'
                    ],
                ]);
            }else{
                return response()->json([
                    'data' => false,
                    'meta' => [
                        'message' => 'Unauthenticated.'
                    ],
                ], 401);
            }
        }

        return response()->json(['error' => 'Unauthenticated.'], 401);
    }
}
