<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use OhMyBrew\ShopifyApp\Facades\ShopifyApp;
use OhMyBrew\ShopifyApp\Models\Shop;
use OhMyBrew\BasicShopifyAPI;
use Response;

class AppController extends Controller
{
	
	public function index() 
    {
    	$shop = ShopifyApp::shop();

	    $result = $shop->api()->request('GET', '/admin/shop.json');

	    $products = $shop->api()->request('GET', '/admin/products.json')->body->products;

	    $pages = $shop->api()->request('GET', '/admin/pages.json')->body->pages;

	    $articles = $shop->api()->request('GET', '/admin/articles.json')->body->articles;

	    return view('index', compact('shop', 'products', 'pages', 'articles', 'shop'));
	}

    // After authentication
	public function billing() {
		$shop = ShopifyApp::shop();

		$charge_data = array
		(
			"recurring_application_charge" => array(
				"name" => "Monthy Plan",
		    	"price" => 4.99,
		    	"test" => true,
		    	"return_url" => "https://fdc4c9a6.ngrok.io/activeCharge",
		    	"capped_amount" => 1,
		    	"terms" => "$4.99 per month"
			)
		);

	    $result = $shop->api()->request('POST', '/admin/recurring_application_charges.json', $charge_data);

	    $confirmation_url = $result->body->recurring_application_charge->confirmation_url;

	    return Redirect::to($confirmation_url);
	}

	// Controller for activeCharge page
	public function activeCharge(Request $request)
	{
		// Get Charge id from url
		$charge_id = $request->input('charge_id');

		// Check if charge status is accepted or declined
		$shop = ShopifyApp::shop();

		$shop->charge_id = $charge_id;

		$shop->save();

		$result = $shop->api()->request('GET', '/admin/recurring_application_charges/' . $charge_id . '.json');

		$charge_status = $result->body->recurring_application_charge->status;

		if($charge_status == "accepted") {
			// Active charge
			$active_data = array (
				"recurring_application_charge" => array (
				    "id" => $charge_id,
				    "name" => "Monthy Plan",
				    "api_client_id" => null,
				    "price" => "4.99",
				    "status" => "accepted",
				    "return_url" => "/admin/apps/" . config('shopify-app.api_key'),
				    "billing_on" => null,
				    "created_at" => null,
				    "updated_at" => null,
				    "test" => null,
				    "activated_on" => null,
				    "trial_ends_on" => null,
				    "cancelled_on" => null,
				    "trial_days" => 0,
				    "decorated_return_url" => "https://fdc4c9a6.ngrok.io/activeCharge?charge_id=" + $charge_id,
				)
			);

		$result2 = $shop->api()->request('POST', '/admin/recurring_application_charges/' . $charge_id . '/activate.json');
		
		return redirect('https://' . $shop->shopify_domain . '/admin/apps/' . config('shopify-app.api_key'));

		} else if($charge_status == "declined") {
			// return to app page with message example: you do not charge app so you can't use it.
		}
	}

	// Create Redirect
	// Save Url
	public function api_redirect(Request $request)
	{
		$shop = ShopifyApp::shop();

		$path = $request->json('path');

		$target = $request->json('target');

		$url = '/admin/redirects.json';

		$data = array
		(
			'redirect' => array(
				'path' => $path,
				'target' => $target
			)
		);

		$response = $shop->api()->request('POST', $url, $data);
	}
}
