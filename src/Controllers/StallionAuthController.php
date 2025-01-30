<?php

namespace StallionExpress\AuthUtility\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiSuccessResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use StallionExpress\AuthUtility\Trait\STEncodeDecodeTrait;

class StallionAuthController extends Controller
{
    use STEncodeDecodeTrait;

    public $url;

    public $client_secret;

    public $client_id;

    public $redirect_uri;

    public function __construct()
    {
        $this->url = config('stallionauthutility.authservice.url');
        $this->client_secret = config('stallionauthutility.authservice.client_secret');
        $this->client_id = config('stallionauthutility.authservice.client_id');
        $this->redirect_uri = config('stallionauthutility.app_url') . '/auth/callback';
    }

    /**
     * function is used to redirect the user to the auth server
     *
     * @return Redirect
     */
    public function login(Request $request)
    {
        $state = (string) Str::ulid();
        Cache::put('link_' . $state, $_SERVER['HTTP_REFERER'] ?? config('stallionauthutility.authservice.front_end_url') . '/dashboard', $seconds = 120);
        $url = config('stallionauthutility.authservice.url') . '/redirect?client_id=' . config('stallionauthutility.authservice.client_id') . '&redirect_uri=' . config('app.url') . '/auth/callback' . '&state=' . $state;
        return redirect($url);
    }

    /**
     * function is used to fetch tokens from the auth server
     *
     * @return Redirect
     */
    public function getAccessToken(Request $request)
    {
        $response = Http::asForm()->post($this->url . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'code' => $request->code,
        ]);

        $data = $response->json();
        if (isset($data['error'])) {
            return new ApiSuccessResponse(
                '',
                ['message' => trans('common.error.not_authorized')],
                Response::HTTP_UNAUTHORIZED
            );
        }

        unset($data['refresh_token']);
        Cache::forget('state');
        $data['redirect_link'] = Cache::get('link_' . $request->state);
        Cache::put($request->state, $data, $seconds = 120);
        Cache::forget('link_' . $request->state);
        $stateValue = $this->encodeHashValue($request->state);

        return Redirect::to(config('stallionauthutility.authservice.front_end_url').'login/' . '?key=' . $stateValue);
    }

    /**
     * This function is used to return an access token to the user
     *
     * @return response
     */
    public function returnToken(string $id)
    {
        $token = Cache::get($id);
        if ($token) {
            Cache::forget($id);

            $response = Http::withToken($token['access_token'])
                ->withHeaders([
                    'Accept' => 'application/json',
                ])->get(config('stallionauthutility.authservice.url') . '/api/oauth/token/user');

            $userDetails = null;
            if ($response->successful()) {
                $userDetails = $response->json();
            }

            return response()->json([
                'token' => $token,
                'user' => $userDetails,
            ]);
        }

        return response()->json(['error' => 'Your token has been expired'], 401);
    }

    /**
     * Function will return user scopes
     *
     * @param int $role
     * @return response
     */
    public function threeplFeatures()
    {
        return response()->json(['scopes' => getFeaturesForThreepl()], 200);
    }

    public function userScopes()
    {
        return response()->json(['scopes' => getSystemFeatures()], 200);
    }
}
