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

Route::get('/', 'AppController@index')->middleware('auth.shop', 'auth.charge')->name('home');

Route::get('/billing', 'AppController@billing')->middleware('auth.shop')->name('billing');

Route::get('/activeCharge', 'AppController@activeCharge')->middleware('auth.shop');

Route::post('/api/redirect', 'AppController@api_redirect')->middleware('auth.shop');

// Product

Route::get('/meta-tags', 'ProductController@metatags')->middleware('auth.shop')->name('meta-tags');

Route::get('/api/meta-tags', 'ProductController@api_metatags')->middleware('auth.shop');

Route::get('/api/product', 'ProductController@api_product')->middleware('auth.shop');

Route::get('/api/shop', 'ProductController@api_shop')->middleware('auth.shop');

Route::post('/api/save/{type}', 'ProductController@api_save')->middleware('auth.shop');

Route::put('/api/save-url', 'ProductController@api_save_url')->middleware('auth.shop');

// Page

Route::get('/meta-tags-page', 'PageController@metatags')->middleware('auth.shop')->name('meta-tags-page');

Route::get('/api/meta-tags-page', 'PageController@api_metatags')->middleware('auth.shop');

Route::get('/api/page', 'PageController@api_page')->middleware('auth.shop');

Route::post('/api/save-page/{type}', 'PageController@api_save')->middleware('auth.shop');

Route::put('/api/save-page-url', 'PageController@api_save_url')->middleware('auth.shop');

// Blog Post

Route::get('/meta-tags-post', 'PostController@metatags')->middleware('auth.shop')->name('meta-tags-post');

Route::get('/api/meta-tags-post', 'PostController@api_metatags')->middleware('auth.shop');

Route::get('/api/post', 'PostController@api_post')->middleware('auth.shop');

Route::post('/api/save-post/{type}', 'PostController@api_save')->middleware('auth.shop');

Route::put('/api/save-post-url', 'PostController@api_save_url')->middleware('auth.shop');

Route::get('/api/blog', 'PostController@api_blog')->middleware('auth.shop');


