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
    return view('admin');
});

/********************************
 * WORDPRESS
 *******************************/
Route::get('/', 'WPAdminController@show');
Route::get('wp/admin', 'WPAdminController@show');
Route::get('wp/posts/list/{categoryId?}', 'WPPostController@show');
Route::get('wp/posts/import/', 'WPPostController@import');
Route::get('wp/posts/update/', 'WPPostController@update');
Route::get('wp/categories/list', 'WPCategoriesController@show');
Route::get('wp/categories/import/{categoryId}', 'WPCategoriesController@import');
Route::get('wp/categories/syncWithFirestore/', 'WPCategoriesController@syncWithFirestore');

/********************************
 * FIRESTORE
 *******************************/
Route::get('fs/posts/list', 'FSPostController@show');
Route::get('fs/posts/delete/{postId}', 'FSPostController@delete');

