<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Str;


class TwitterAuthController extends Controller
{
    
    public function redirect(Request $request){
        $request->session()->put('state', $state = Str::random(40));
        $query = http_build_query([
            'client_id' => 16,
            'redirect_uri' => 'http://fresher/callback',
            'response_type' => 'code',
            'scope' => 'view-tweets post-tweets',
            'state' => $state,
        ]);
        return redirect('http://fresh/oauth/authorize?' . $query);
    }
    
    public function callback(Request $request){
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
        $response = json_decode($response->getBody());
        
        
        $request->user()->token()->delete();
        $request->user()->token()->create([
            'access_token' => $response->access_token,
            'refresh_token' => $response->refresh_token,
            'expires_in' => $response->expires_in,
        ]);
        return redirect()->route('home'); 
    }
    public function refresh(Request $request){
        $response = Http::asForm()->post('http://fresh/oauth/token', [
            'grant_type'=> 'refresh_token',
            'refresh_token' => $request->user()->token->refresh_token,
            'client_id' => 16,
            'client_secret' => '0dnwhetTZAmpRYMOqWyqDu35FkK9AOCeh5yIYHMy',
            'scope' => 'view-tweets post-tweets',
        ]);
        $response = json_decode($response->getBody());
        $request->user()->token()->update([
            'access_token' => $response->access_token,
            'refresh_token' => $response->refresh_token,
            'expires_in' => $response->expires_in,
        ]);
        return redirect()->route('home'); 
    }
    
}
