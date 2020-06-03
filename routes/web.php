<?php

use Illuminate\Support\Facades\Route;

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
    return view('landing');
});

Route::get('error', function() {
    return view('error');
});

//Auth routes
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Response routes

Route::get('slambook/welcome/{userId}', 'ResponseController@getWelcomePage');
Route::get('slambook/{page}', 'ResponseController@getSlambook');
Route::get('slambook/response/{id}', 'ResponseController@show');
Route::get('slambook', 'ResponseController@getSlambook');
Route::post('slambook/navigateSlambook/{action}', 'ResponseController@navigateSlambook');
Route::post('slambook/store', 'ResponseController@store');

//Final route 
Route::get('responses/final', function() {
    return view('responses/final');
});

//Questions route
Route::resource('questions', 'QuestionController');

