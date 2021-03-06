<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use OhMyBrew\ShopifyApp\Models\Shop;
use OhMyBrew\BasicShopifyAPI;
use Response;

class ProductController extends Controller
{

    public function __construct() 
    {
    	$this->middleware('auth.charge');
	}

	// Return view for Meta tags
	public function metatags()
	{
		if(isset($_GET['id'])) {
			$product_id = $_GET['id'];
		} else {
			$product_id = $_GET['product_id'];
		}

		$type = 'products';

		return view('meta-tags', compact('product_id', 'type'));
	}

	// Get Meta tags value of specific product
	public function api_metatags() {

		$shop = ShopifyApp::shop();

		$product_id = Input::get('product_id');

		$url = '/admin/products/'. $product_id .'/metafields.json';

	    $result = $shop->api()->request('GET', $url);

	    return response()->json($result);
	}

	// Get Product information
	public function api_product() {
		$shop = ShopifyApp::shop();

		$product_id = Input::get('ids');

		$url = '/admin/products.json?ids='. $product_id;

	    $result = $shop->api()->request('GET', $url);

	    return response()->json($result);
	}

	// Get Shop information
	public function api_shop() {
		$shop = ShopifyApp::shop();

		$url = '/admin/shop.json';

	    $result = $shop->api()->request('GET', $url);

	    return response()->json($result);
	}

	// Save Data
	public function api_save(Request $request, $type) {

		$shop = ShopifyApp::shop();

		$product_id = $request->json('id');

		$url = '/admin/products/' . $product_id . '/metafields.json';

		if($type == 'title') {
			$meta_title = $request->json('title_value');

			$meta = array
		    (
	    		'metafield' => array(
	    			'namespace' => 'global',
	    			'key' => 'title_tag',
	    			'value' => $meta_title,
	    			'value_type' => 'string'
	    		)
	    	);
		} else if($type == 'desc') {
			$meta_desc = $request->json('desc_value');

			$meta = array
		    (
	    		'metafield' => array(
	    			'namespace' => 'global',
	    			'key' => 'description_tag',
	    			'value' => $meta_desc,
	    			'value_type' => 'string'
	    		)
	    	);
		}

		$response = $shop->api()->request('POST', $url, $meta);
	}

	// Save Url
	public function api_save_url(Request $request)
	{
		$shop = ShopifyApp::shop();

		$custom_handle = $request->json('url_value');

		$product_id = $request->json('id');

		$url = '/admin/products/' . $product_id . '.json';

		$data = array
		(
			'product' => array(
				'handle' => $custom_handle
			)
		);

		$response = $shop->api()->request('PUT', $url, $data);
	}
}
