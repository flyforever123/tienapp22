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

	    $result = $shop->api()->request([ 
	        'METHOD'    => 'GET', 
	        'URL'       => '/admin/products.json?page=1' 
	    ]); 
	    $products = $result->products; 

	    return view('welcome', compact('products'));
	}
}
