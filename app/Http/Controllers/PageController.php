<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use OhMyBrew\ShopifyApp\Models\Shop;
use OhMyBrew\BasicShopifyAPI;
use Response;

class PageController extends Controller
{
    // Return view for Meta tags
	public function metatags()
	{
		if(isset($_GET['id'])) {
			$page_id = $_GET['id'];
		} else {
			$page_id = $_GET['page_id'];
		}

		$type = 'pages';

		return view('meta-tags-page', compact('page_id', 'type'));
	}

	// Get Meta tags value of specific page
	public function api_metatags() {

		$shop = ShopifyApp::shop();

		$page_id = Input::get('page_id');

		$url = '/admin/pages/'. $page_id .'/metafields.json';

	    $result = $shop->api()->request('GET', $url);

	    return response()->json($result);
	}

	// Get Page information
	public function api_page() {
		$shop = ShopifyApp::shop();

		$page_id = Input::get('ids');

		$url = '/admin/pages/'. $page_id . '.json';

	    $result = $shop->api()->request('GET', $url);

	    return response()->json($result);
	}

	// Save Data
	public function api_save(Request $request, $type) {

		$shop = ShopifyApp::shop();

		$page_id = $request->json('id');

		$url = '/admin/pages/' . $page_id . '/metafields.json';

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

		$page_id = $request->json('id');

		$url = '/admin/pages/' . $page_id . '.json';

		$data = array
		(
			'page' => array(
				'handle' => $custom_handle
			)
		);

		$response = $shop->api()->request('PUT', $url, $data);
	}
}
