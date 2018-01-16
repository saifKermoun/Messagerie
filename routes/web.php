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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'ConversationsController@index')->name('home');
Route::get('/conversations}', 'ConversationsController@index')->name('conversations');
Route::get('/conversations/{user}', 'ConversationsController@show')
    ->middleware('can:talkTo,user')
    ->name('conversations.show');
Route::post('/conversations/{user}', 'ConversationsController@store')->middleware('can:talkTo,user');

