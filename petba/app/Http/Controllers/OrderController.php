<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderOption;
use App\Models\OrderProduct;
use App\Models\OrderStatus;
use App\Models\OrderTotal;
use App\Models\Address;
use App\Models\Country;
use App\Models\Zone;
use App\Models\User;
use App\Models\Cart;
use App\Models\ProductOptionValue;


class OrderController extends Controller
{
    public function index(Request $request) 
    {
    	// List of orders based on the user Id
    	$user_id = $request->user_id;

		if (!empty($user_id)) {
			$order = Order::where('customer_id', $user_id)->with([
				'order_products' => function($query) {
					$query->with([
						'product_image' => function($query) {
							$query-> select('product_id', 'image');
						}
					]);
				}
			])->get();
			if(!empty($order[0])) {
				return ['success' => true, 'order' => $order];
			}
			return ['success' => false, 'message' => 'Error: User doesn\'t have order history'];

		} else {
			return response()->json([
                'success' => false,
                'message' => 'Login to continue',
            ], 401);
		}
    }





    public function store(Request $request)
    {
    	$user_id = $request->user_id;
		// $user_id = 1;
		if (!empty($user_id)) {
			//Need to get the address_id 
			$address = Address::where('address_id', $request->address_id)->first();
			// return $address;
			$user = User::where('customer_id', $address->customer_id)->first(['email', 'telephone']); 
			$country = Country::where('country_id', $address->country_id)->first('name');
			$zone = Zone::where('zone_id', $address->zone_id)->first('name');
			$cart = Cart::where('customer_id', $address->customer_id)->get();

			if($request->payment_id == 1) {
				$payment_method = 'Cash On Delivery';
				$payment_code = 'cod';
			} elseif ($request->payment_id == 2) {
				$payment_method = 'RazorPay';
				$payment_code = 'razor';
			}

			$order = Order::create([
				'invoice_no' => 0,
				'invoice_prefix' => "INV".date("-Y-")."00",
				'store_name' => "Petba",
				'store_url' => "https://petba/",
				'customer_id' => $user_id,
				'customer_group_id' => 1,
				'firstname' => $address->firstname,
				'lastname' => $address->lastname,
				'email' => $user->email,
				'telephone' => $user->telephone,
				'fax' => '',
				'payment_firstname' => $address->firstname,
				'payment_lastname' => $address->lastname,
				'payment_address_1' => $address->address_1,
				'payment_address_2' => $address->address_2,
				'payment_city' => $address->city,
				'payment_postcode' => $address->postcode,
				'payment_country' => $country->name,
				'payment_country_id' => $address->country_id,
				'payment_zone' => $zone->name,
				'payment_zone_id' => $address->zone_id,
				'shipping_firstname' => $address->firstname,
				'shipping_lastname' => $address->lastname,
				'shipping_address_1' => $address->address_1,
				'shipping_city' => $address->city,
				'shipping_postcode' => $address->postcode,
				'shipping_country' => $country->name,
				'shipping_country_id' => $address->country_id,
				'shipping_zone' => $zone->name, 
				'shipping_zone_id' => $address->zone_id,

				'payment_method' => $payment_method,  
				'payment_code' => $payment_code,   
				'shipping_method' => "Flat Shipping Rate",//$request->shipping_method,     //
				'shipping_code' => "flat.flat",//$request->shipping_code,         //
				'total' => $request->total,                         //
				'order_status_id' => 1,
				'affiliate_id' => 0,
				'language_id' => 1,
				'currency_id' => 5,
				'currency_code' => "INR",
				'currency_value' => 1.00,
				'accept_language' => "en-US,en;q=0.5",
				'date_added' => date("Y-m-d h:i:s"),
				'date_modified' => date("Y-m-d h:i:s"),
			]); 
			
			if($order) {
				$cart_products = Cart::where('customer_id', $address->customer_id)->with([
					'product' => function($query) {
						$query->select('product_id','model','price');
					},
					'product_description' => function($query) {
						$query->select('product_id','name');
					}
				])->get();

				if($cart_products) {
				foreach ($cart_products as $key => $cart) {
					$cart->option = json_decode($cart->option);
					$optionsList = array();
					foreach ($cart->option as $productOptionId => $productOptionValueId) {
							$optionValue = ProductOptionValue::where([
								'product_option_id' => $productOptionId,
								'product_option_value_id' => $productOptionValueId
							])->with([
	                        	'option_category' => function($query) {
	                        		$query->select('option_id', 'name');
	                        	},
	                        	'option_value' => function($query) {
	                        		$query->select('option_value_id', 'name');
	                        	},
	                        ])->first();
							array_push($optionsList,$optionValue);
						}
						$cart->option = $optionsList;
					}
				}
				foreach ($cart_products as $key => $product) {
					$order_product = OrderProduct::create([
						'order_id' => $order->order_id,
						'product_id'=> $product->product_id,
						'master_id' => 0,
						'name' => $product->product_description->name,
						'model' => $product->product->model,
						'quantity' => $product->quantity,
						'price' => $product->product->price,
						'total' => $product->product->price * $product->quantity,
						'tax' => 0.0,
						'reward' => 0,
					]);
					if($order_product) {
						foreach ($product->option as $key => $option) {
							$data = OrderOption::create([
								'order_id' => $order->order_id,
								'order_product_id' => $order_product->order_product_id,
								'product_option_id' => $option->product_option_id,
								'product_option_value_id' => $option->product_option_value_id,
								'name' => $option->option_category->name,
								'value' => $option->option_value->name,
								'type' => 'radio'
							]);
							if(!$data) {
								return ['success' => false, 'message' => 'Error: Data didn\'t go into the order option table'];
							}
						}
					} else {
						return ['success' => false, 'message' => 'Error: Data didn\'t go into the order product table'];
					}
				}
				$sub_total = OrderTotal::create([
					'order_id' => $order->order_id,
					'extension' => 'opencart',
					'code' => 'sub_total',
					'title' => 'Sub-Total',
					'value' => $request->total,
					'sort_order' => 1
				]);
				if($sub_total) {
					$shipping = OrderTotal::create([
						'order_id' => $order->order_id,
						'extension' => 'opencart',
						'code' => 'shipping',
						'title' => 'Flat Shipping Rate',
						'value' => 5.000,
						'sort_order' => 3
					]);
					if($shipping) {
						$total = OrderTotal::create([
							'order_id' => $order->order_id,
							'extension' => 'opencart',
							'code' => 'total',
							'title' => 'Total',
							'value' => $sub_total->value + $shipping->value,
							'sort_order' => 9
						]);
						if($total) {
							return ['success' => true, 'message' => 'Data went into the order table'];
						} else {
							['success' => false, 'message' => 'Error: Total didn\'t go into the order total table'];
						}							
					} else {
						['success' => false, 'message' => 'Error: Shipping didn\'t go into the order total table'];
					}
				}
			} else {
				return ['success' => false, 'message' => 'Error: SubTotal didn\'t go into the order table'];
			}	
		} else {
			return response()->json([
                'success' => false,
                'message' => 'Login to continue',
            ], 401);
		}
    }


	public function track(Request $request) 
    {
    	// To track the order which are on the way
    	$user_id = $request->user_id;

		if (!empty($user_id)) {
			/////////////////////////////////////////////////
			$track;
			if(!empty($track)) {
				return ['success' => true, 'order' => $track];
			}
			return ['success' => false, 'message' => 'Error: Some Error Occured'];

		} else {
			return response()->json([
                'success' => false,
                'message' => 'Login to continue',
            ], 401);
		}
    }

}
