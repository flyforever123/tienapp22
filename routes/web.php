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
	$shop = ShopifyApp::shop();

    $shopify = App::make('ShopifyAPI', [ 
        'API_KEY'       => $shop->api()->setApiKey(), 
        'API_SECRET'    => $shop->api()->setApiSecret(), 
        'SHOP_DOMAIN'   => $shop->api()->getShop(), 
        'ACCESS_TOKEN'  => $shop->api()->getAccessToken() 
    ]);

    // Gets a list of products 
    $result = $shopify->call([ 
        'METHOD'    => 'GET', 
        'URL'       => '/admin/products.json?page=1' 
    ]); 
    $products = $result->products; 

    return view('welcome', compact('products'));
});