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
	// Gets a list of products 
    $result = ShopifyApp::shop();
    $result->api()->request([ 
        'METHOD'    => 'GET', 
        'URL'       => '/admin/products.json?page=1' 
    ]); 
    $products = $result->products; 

    return view('welcome', compact('products');
});