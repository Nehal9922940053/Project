<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\FilterGroup;
use App\Models\Filter;

class ProductController extends Controller
{
    public function index (Request $request) 
    {
    	$user_id = $request->user_id;
    	// $user_id=1;

    	$products = Product::with([
                'manufacturer' => function($query) {
                    $query->select('manufacturer_id', 'name');
                },
        		'product_description' => function($query) {
        			$query->select('product_id', 'name', 'description');
        		},
        		'product_image' => function($query) {
        			$query->select('product_id', 'image');
        		},
        		'weight_class_id' => function($query) {
        			$query->select('weight_class_id', 'unit');
        		},
                'product_option_category' => function($query) {
                    $query->with([
                        'product_category_name' => function($query) {
                            $query->select('option_id', 'name');
                        },
                        'product_options' => function($query) {
                            $query->with([
                                'option_value' => function($query) {
                                    $query->select('option_value_id', 'name');
                                }
                            ]);
                        }
                    ])->select('product_option_id', 'product_id', 'option_id','required');
                }
        	])->get();

        if($products) {
        	foreach ($products as $key => $product) {
                if(strlen($product->product_description->name) > 29) {
                    $product->product_description->name =  substr($product->product_description->name, 0, 29).'...';      
                }

        		$wishlist = Wishlist::where([
    				'customer_id'=>$user_id,
    				'product_id'=>$product->product_id,
    			])->first();

    			if(empty($wishlist)){
    				$product['wishlist'] = false;
    			}else{
    				$product['wishlist'] = true;
    			}
        	}
        	return ['success' => true, 'products' => $products];
        } else {
            return ['success' => false, 'message' => 'Some error occured'];
        }
    }



    public function filter($id) 
    {
    	return Filter::where('filter_group_id', $id)->get();
    	 
    }



    public function filter_group() {
    	return FilterGroup::select('filter_group_id', 'name')->get();
    }




    public function read ($id, Request $request)
    {
    	$user_id = $request->query('user_id');
    	$user_id=1;

    	$product = Product::where('product_id', $id)->with([
            'manufacturer' => function($query) {
                $query->select('manufacturer_id', 'name');
            },
    		'product_description' => function($query) {
    			$query->select('product_id', 'name', 'description');
    		},
    		'product_image' => function($query) {
    			$query->select('product_id', 'image');
    		},
    		'weight_class_id' => function($query) {
        		$query->select('weight_class_id', 'unit');
        	},
            'product_option_category' => function($query) {
                    $query->with([
                        'product_category_name' => function($query) {
                            $query->select('option_id', 'name');
                        },
                        'product_options' => function($query) {
                            $query->with([
                                'option_value' => function($query) {
                                    $query->select('option_value_id', 'name');
                                }
                            ]);
                        }
                    ])->select('product_option_id', 'product_id', 'option_id','required');
                }
    	])->first();

        if($product) {
        	$wishlist = Wishlist::where([
    			'customer_id'=>$user_id,
    			'product_id'=>$product->product_id,
    		])->first();
     
    		if(empty($wishlist)) {
    			$product->wishlist = false;
    		} else {
    			$product->wishlist = true;
    		}
        	return ['success' => true, 'product' => $product];
        } else {
            return ['success' => false, 'message' => 'Couldn\'t find product'];
        }
    }



    public function wishlist_add (Request $request) 
    {
    	$user_id = $request->user_id;
		$wishlist = Wishlist::where(['customer_id'=> $user_id , 'product_id' => $request->product_id])->first();
		
		if(empty($wishlist)){
			$wishlist = Wishlist::create([
                'customer_id' => $user_id,
                'product_id' => $request->product_id,
                'date_added' => date("Y-m-d h:i:s"),
            ]);
			if($wishlist) {
                return ['success' => true, 'message' => 'Added successfully'];
            } else {
                return ['success' => false, 'message' => 'Wasn\'t added'];
            }
		} else {
            return ['success' => false, 'message' => 'Some error happened'];
        }
    }



    public function wishlist_remove (Request $request) 
    {
    	$user_id = $request->user_id;
        if(!empty($user_id)) {
    		$wishlist = Wishlist::where(['customer_id'=> $user_id , 'product_id' => $request->product_id])->delete();
    		
    		if($wishlist){
    			return ['success' => true, 'message' => 'Deleted successfully'];
    		} else {
    			return ['success' => false, 'message' => 'Row not present so nothing to delete'];
    		}
        } else {
            return ['success' => false, 'message' => 'Login to continue'];
        }       
    }

}
