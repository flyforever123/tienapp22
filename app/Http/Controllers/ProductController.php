<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use OhMyBrew\ShopifyApp\Models\Shop;

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
