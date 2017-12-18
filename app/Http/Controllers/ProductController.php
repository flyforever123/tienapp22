<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use OhMyBrew\ShopifyApp\Models\Shop;
use OhMyBrew\BasicShopifyAPI;

class ProductController extends Controller
{
    public function index() 
    {
		$shop = ShopifyApp::shop();

	    $result = $shop->api()->request('GET', '/admin/products.json?page=1');

	    $products = json_decode($result->body); 

	    return view('welcome', compact('products'));
	}
}
