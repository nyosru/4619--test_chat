<?php

// use App\Room;
use Illuminate\Http\Request;

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

    return view('room', ['room' => 12]);
    return view('chat');
});

// Route::post('messages', function( Illuminate\Http\Request $request) {
Route::post('messages', function(  Request $request ) {

	App\Events\PrivateChat::dispatch($request->all());

});

// Route::get('/room/{room}', function( App\Room $room) {
Route::get('/room/{room}', function( ) {

	return view('room', ['room' => 12 ]);

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
