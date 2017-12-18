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

	    $script_tag = array
	    (
    		'script_tag' => array(
    			'event' => 'onload',
    			'src' => 'https://tienapp22.herokuapp.com/js/script.js'
    		)
    	);

	    $response = $shop->api()->request('POST', '/admin/script_tags.json', $script_tag);

	    $products = $result->body->products; 

	    return view('welcome', compact('products'));
	}
}
