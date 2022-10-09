<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Services\Service;

class TwitterAuthController extends Controller
{
    protected $service;
    public function __construct(Service $service) {
        $this->service = $service;
    }

    public function redirect(Request $request){
        $query = $this->service->queryBuilder($request);
        return redirect('http://fresh/oauth/authorize?' . $query);
    }
    
    public function callback(Request $request){
        $response = $this->service->callback($request);
        if($response->ok()){
            $this->service->saveToken($response, $request);
            return redirect()->route('home'); 
        } else {
            return redirect()->route('home')->withErrors("You are denied the request"); 
        }
    }
    public function refresh(Request $request){
        $response = $this->service->refresh($request);
        $this->service->updateToken($response, $request);
        return redirect()->route('home'); 
    }
    
}
