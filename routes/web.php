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
    return view('welcome');
});

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');


// polls
Route::get('/home', 'PollController@index')->name('home');
Route::get('/polls', 'PollController@index')->name('poll.index');
Route::get('/polls/my', 'PollController@mypolls')->name('poll.my');
Route::get('/polls/login', 'PollController@viewLogRedir')->name('poll.login');
Route::get('/polls/create', 'PollController@createPoll')->name('poll.create');
Route::post('/polls/docreate', 'PollController@docreatePoll')->name('poll.docreate');
Route::post('/polls/publish', 'PollController@publishPoll')->name('poll.publish');
Route::post('/polls/delete', 'PollController@deletePoll')->name('poll.delete');
Route::post('/polls/addopt', 'PollController@addOption')->name('poll.addopt');
Route::post('/polls/remopt', 'PollController@removeOption')->name('poll.remopt');
Route::get('/polls/view', 'HomeController@viewPoll')->name('poll.view');
Route::post('/polls/vote', 'HomeController@vote')->name('poll.vote');
