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

Route::get('/{feedId?}', 'FeedController@index')->name('home');

Route::group(['prefix' => 'feed'], function () {
    Route::post('get_all', 'FeedController@getAll');
    Route::post('add', 'FeedController@add');
    Route::post('update', 'FeedController@update');
    Route::post('remove', 'FeedController@remove');

    Route::post('get_posts', 'FeedPostController@getPosts');
    Route::post('mark_read', 'FeedPostController@markAsRead');
    Route::post('mark_unread', 'FeedPostController@markAsUnRead');
});