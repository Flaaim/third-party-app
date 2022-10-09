<?php

namespace App\Http\Services;

use Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Service {

    public function queryBuilder($request){
        $request->session()->put('state', $state = Str::random(40));
        $query = http_build_query([
            'client_id' => 16,
            'redirect_uri' => 'http://fresher/callback',
            'response_type' => 'code',
            'scope' => 'view-tweets post-tweets',
            'state' => $state,
        ]); 
        return $query;
    }

    public function callback($request){
        $state = $request->session()->pull('state');
        
        throw_unless(
            strlen($state) > 0 && $state === $request->state,
            InvalidArgumentException::class,
        );
        $response = Http::asForm()->post('http://fresh/oauth/token', [
                'grant_type' => 'authorization_code',
                'client_id' => 16,
                'client_secret' => '0dnwhetTZAmpRYMOqWyqDu35FkK9AOCeh5yIYHMy',
                'redirect_uri' => 'http://fresher/callback',
                'code' => $request->code,
        ]);
        return $response;
           
    }

    public function saveToken($response, $request){
        $response = json_decode($response->getBody());
            $request->user()->token()->delete();
            $request->user()->token()->create([
                'access_token' => $response->access_token,
                'refresh_token' => $response->refresh_token,
                'expires_in' => $response->expires_in,
            ]);
    }

    public function refresh($request){
       Http::asForm()->post('http://fresh/oauth/token', [
            'grant_type'=> 'refresh_token',
            'refresh_token' => $request->user()->token->refresh_token,
            'client_id' => 16,
            'client_secret' => '0dnwhetTZAmpRYMOqWyqDu35FkK9AOCeh5yIYHMy',
            'scope' => 'view-tweets post-tweets',
        ]);
        return $response;
    }

    public function updateToken($response, $request){
        $response = json_decode($response->getBody());
        $request->user()->token()->update([
            'access_token' => $response->access_token,
            'refresh_token' => $response->refresh_token,
            'expires_in' => $response->expires_in,
        ]);
    }
}