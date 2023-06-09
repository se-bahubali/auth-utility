<?php

namespace StallionExpress\AuthUtility\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class StallionAuthTokenGuard implements Guard
{
    use GuardHelpers;

    private $inputKey = '';

    private $storageKey = '';

    private $request;

    public function __construct(UserProvider $provider, Request $request, $configuration)
    {
        $this->provider = $provider;
        $this->request = $request;
        // key to check in request
        $this->inputKey = isset($configuration['input_key']) ? $configuration['input_key'] : 'access_token';
        // key to check in database
        $this->storageKey = isset($configuration['storage_key']) ? $configuration['storage_key'] : 'access_token';
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }

        $user = null;

        // retrieve via token
        $token = $this->getTokenForRequest();

        if (!empty($token)) {
            // the token was found, how you want to pass?
            $user = $this->provider->retrieveByToken($this->storageKey, $token);
            if($user){
                $this->setUser($user);
            }            
        }      

        return $this->user;
    }

    /**
     * Get the token for the current request.
     *
     * @return string
     */
    public function getTokenForRequest()
    {
        $token = $this->request->query($this->inputKey);

        if (empty($token)) {
            $token = $this->request->input($this->inputKey);
        }

        if (empty($token)) {
            $token = $this->request->bearerToken();
        }

        return $token;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return false;
    }
}
