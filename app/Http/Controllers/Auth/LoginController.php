<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
//use http\Client\Request;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

//    public function showLoginForm(Request $request){
////        $client_id = $request->get('client_id');print_r($client_id); die();
//        $client_id = 1;
//
//        return view('vendor.passport.authorize', compact($client_id));
//    }
}
