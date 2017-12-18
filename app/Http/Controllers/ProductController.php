<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() 
    {
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
	}
}
