<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('barcode', 'HomeController@barcode');

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
// $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
// $this->post('register', 'Auth\RegisterController@register');

// Password reset Routes...

$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::post('oauth/token', 'Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');


Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', function() {
    return view('welcome');
})->middleware('guest');
// Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
