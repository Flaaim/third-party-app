<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $tweets = collect();
        if($request->user()->token){
            $response = Http::withToken($request->user()->token->access_token)->accept('application/json')->get('http://fresh/api/tweets');
            $tweets = collect(json_decode($response->getBody()));    
        }
        return view('home', ['tweets' => $tweets]); 
    }
}
