<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', 'Auth\AuthController@getLogin');
Route::post('login', 'Auth\AuthController@postLogin');

Route::get('logout', 'Auth\AuthController@getLogout');

Route::get('register', 'Auth\AuthController@getRegister');
Route::post('register', 'Auth\AuthController@postRegister');

Route::get('new', ['uses' => 'HubController@create', 'middleware' => 'auth']);
Route::post('new', ['uses' => 'HubController@store', 'middleware' => 'auth']);

Route::get("/hub/{title}", "HubController@show");

Route::get('/home', ['middleware' => 'auth', 'uses' => 'UserController@home']);

Route::get('/hub/{title}/new', ['middleware' => 'auth', 'as' => 'post.create', 'uses' => 'PostController@create']);
Route::post('/hub/{title}/new', 'PostController@store');

Route::post('/subscribe/{title}', ['as' => 'subscribe', 'uses' => 'HubController@subscribe']);
Route::post('/unsubscribe/{title}', ['as' => 'unsubscribe', 'uses' => 'HubController@unsubscribe']);

Route::get("/hub/{title}/{slug}", ['as' => 'post', 'uses' => "PostController@show"]);

Route::post('/hub/{title}/{slug}/comment/new', ['as' => 'new comment', 'uses' => 'CommentController@store', 'middleware' => 'auth']);

Route::post('/hub/{title}/{slug}/upvote', ['as' => 'upvote post', 'uses' => 'PostController@upvote']);

Route::post('/hub/{title}/{slug}/downvote', ['as' => 'downvote post', 'uses' => 'PostController@downvote']);

Route::post('/comment/{id}/upvote', 'CommentController@upvote');

Route::get('/explore', 'HubController@index');

Route::get('/users/{username}', 'UserController@show');
Route::post("/post/delete/{id}", "PostController@destroy");

Route::post('sticky/{id}', 'PostController@sticky');