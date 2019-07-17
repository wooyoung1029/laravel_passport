<?php
use GuzzleHttp\Client;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/auth/callback', function (\Illuminate\Http\Request $request){
//    $http = new Client([
//        'base_uri'  => 'http://dev.passport.test/oauth/token',  // 请求地址
//        'timeout'   => 10, // 超时时间
//        'verify'    => false
//    ]);
//
//    $response = $http->request('POST', 'http://dev.passport.test/oauth/token', [
//        'form_params' => [
//            'grant_type' => 'authorization_code',
//            'client_id' => '3',  // your client id
//            'client_secret' => 'wnfeb5fU7JkwQC4daIZ1HFQYSPAKbfwcnIEKLQBF',   // your client secret
//            'redirect_uri' => 'http://dev.passport.test/auth/callback',
//            'code' => $request->code,
//        ],
//    ]);
//
//    return json_decode((string) $response->getBody(), true);
//});


