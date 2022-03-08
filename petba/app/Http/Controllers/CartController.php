<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ProductOptionValue;

class CartController extends Controller
{
    public function index(Request $request)
	{
		$user_id = $request->user_id;
		// $user_id = 1;

		if (!empty($user_id)) {
			$cartProducts = Cart::where('customer_id', $user_id)->with([
				'product' => function($query) {
	    			$query->select('product_id', 'image', 'price', 'weight', 'weight_class_id')->with([
	    				'weight_class_id' => function($query) {
	    					$query->select('weight_class_id', 'unit');
	    				}
	    			]);
	    		},
				'product_description' => function($query) {
	    			$query->select('product_id', 'name');
	    		},
			])->get(['cart_id', 'product_id', 'quantity', 'option']);
			
			if($cartProducts) {
				foreach ($cartProducts as $key => $cart) {
					//To keep the word limit to 30
					if(strlen($cart->product_description->name) > 35) {
						$cart->product_description->name =  substr($cart->product_description->name, 0, 35).'...';		
					}
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

				return ['success' => true, 'cart' => $cartProducts];
			} else {
				return ['sucess' => false, 'message' => 'Cart Empty'];
			}
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
		if  (!empty($user_id)) {
			$cartProduct = Cart::where(['customer_id' => $user_id, 'product_id' => $request->product_id])->first();
			if(!$cartProduct) {
				$cart = Cart::create([
					'customer_id' => $user_id,
					'product_id' => $request->product_id,
					'quantity' => $request->quantity,
					'option' => $request->option,
					'date_added' => date("Y-m-d h:i:s"),
				]);
				if($cart) {
					return ['success' => true, 'message' => 'Added to cart'];
				} else {
					return ['success' => false, 'message' => 'Error occured while adding to cart'];
				}
			} else {
				return ['success' => false, 'message' => 'Already present in cart'];
			}
		} else {
			return response()->json([
                'success' => false,
                'message' => 'Login to continue',
            ], 401);
		}
	}








	public function quantity(Request $request)
	{
		$user_id = $request->user_id;
		if (!empty($user_id)) {
			$data = Cart::where([
				'customer_id' => $user_id,
				'product_id' => $request->product_id
			])->update(['quantity' => $request->quantity]);

			if($data) {
				return ['success' => true, 'message' => 'Update Successful'];
			} else {
				return ['success' => false, 'message' => 'Wasn\'t updated'];
			}
		} else {
			return response()->json([
                'success' => false,
                'message' => 'Login to continue',
            ], 401);
		}
	}

	

	public function remove(Request $request)
	{
		$user_id = $request->user_id;
		if (!empty($user_id)) {
			$data = Cart::where([
				'customer_id'=> $user_id,
				'product_id'=> $request->product_id
			])->delete();
			//$data value gets 1 if product is found.. 1 is true and 0 is false
			if($data) {
				return ['success' => true, 'message' => 'Delete Successful'];
			} else {
				return ['success' => false, 'message' => 'Deletion failed'];
			}
		} else {
			return response()->json([
                'success' => false,
                'message' => 'Login to continue',
            ], 401);
		}
	}

}
