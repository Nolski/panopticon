<?php

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
//    return redirect()->route('login');
    return view('welcome');
});

Route::get('/dashboard', function () {
//    return redirect()->route('login');
    return view('dashboard');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('index/{case_type}', 'ResponseApiController@index');

Route::get('/dashboard', function (){
    return view('dashboard');
});



Route::get('/profile', function () {
    return view('firmProfile');
});

Route::get('/seeker', function () {
    return view('seeker');
});

Route::get('/firms', function () {
    return view('firms');
});