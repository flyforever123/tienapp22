<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use OhMyBrew\ShopifyApp\Models\Shop;
use OhMyBrew\BasicShopifyAPI;
use Response;

class PostController extends Controller
{
    // Return view for Meta tags
	public function metatags()
	{
		if(isset($_GET['id'])) {
			$post_id = $_GET['id'];
		} else {
			$post_id = $_GET['post_id'];
		}

		$type = 'articles';

		return view('meta-tags-post', compact('post_id', 'type'));
	}

	// Get Meta tags value of specific post
	public function api_metatags() {

		$shop = ShopifyApp::shop();

		$post_id = Input::get('post_id');

		$url = '/admin/articles/'. $post_id .'/metafields.json';

	    $result = $shop->api()->request('GET', $url);

	    return response()->json($result);
	}

	// Get post information
	public function api_post() {
		$shop = ShopifyApp::shop();

		$post_id = Input::get('ids');

		$url = '/admin/articles/'. $post_id . '.json';

	    $result = $shop->api()->request('GET', $url);

	    return response()->json($result);
	}

	// Get blog information
	public function api_blog(Request $request) {
		$shop = ShopifyApp::shop();

		$post_id = Input::get('post_id');

		$url = '/admin/articles/'. $post_id . '.json';

	    $article = $shop->api()->request('GET', $url);

	    $blog_id = $article->body->article->blog_id;

	    $blog = $shop->api()->request('GET', '/admin/blogs/' . $blog_id . '.json')->body->blog;

	    $blog_handle = $blog->handle;

	    $blog_id = $blog->id;

	    $blog_info = array(
	    	'blog_info' => array (
		    	'blog_handle' => $blog_handle,
		    	'blog_id' => $blog_id
		    )
	    );

	    return response()->json($blog_info);
	}

	// Save Data
	public function api_save(Request $request, $type) {

		$shop = ShopifyApp::shop();

		$post_id = $request->json('id');

		$url = '/admin/articles/' . $post_id . '/metafields.json';

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

		$article_id = $request->json('id');

		$url = '/admin/articles/' . $article_id . '.json';

		$data = array
		(
			'article' => array(
				'handle' => $custom_handle
			)
		);

		$response = $shop->api()->request('PUT', $url, $data);
	}
}
