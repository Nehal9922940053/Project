<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adopt;
use App\Models\Wishlist;
use App\Models\Breed;
//use App\Models\AnimalType;
use Log;


class AdoptController extends Controller
{
    public function index(Request $request)
	{
		$user_id = $request->query('user_id');
      // 	 $user_id = 2;
		$adoptions = Adopt::with([
		    	'animal_typ',
		    	'breed',
		    	'user'=> function($query){
		    		$query->select('customer_id','display_pic');
		    	},
	    	])->get();

		foreach ($adoptions as $key => $adoption) {
			$adoptionId = $adoption->adopt_id;
			$wishlist = Wishlist::where([
				'customer_id'=>$user_id,
				'adopt_id'=>$adoptionId,
			])->first();
			if(empty($wishlist)) {
				$adoption['wishlist'] = false;
			} else {
				$adoption['wishlist'] = true;
			}
		}

		return $adoptions;
	}


	public function read($id, Request $request)
	{
	    $user_id = $request->query('user_id');
	    $adoption = Adopt::where('adopt_id', $id)->with([
	    	'animal_typ',
	    	'breed',
	    	'user'=> function($query){
		    		$query->select('customer_id','display_pic');
		    	},
	    ])
	    ->first();

	    if($adoption) {
	    	$wishlist = Wishlist::where('adopt_id', $id)->where('customer_id', $user_id)->first();
		    if($wishlist !=null ) {
		    	$adoption->wishlist = true;
		    } else {
		    	$adoption->wishlist = false;
		    }
		    return ['success' => true, 'adoption' => $adoption];
	    } else {
	    	return ['success' => false, 'message' => 'Error: Couldn\'t get data'];
	    }
	}



	public function store(Request $request) 
	{
		$user_id = $request->user_id;
		// Log::info($request);
		// return $request->all();

		if (!empty($user_id)) {

		//check this part.. took from the original code
		/*
		$imageArray = array(1=>$request->img1, 2=>$request->img2, 3=>$request->img3, 4=>$request->img4);
		foreach($imageArray as $x => $value){
    		if($value!=''){
    		$target_path = 'adoptionImage/mypet_'.$request->user_id.'_'.$x.'_'.time().'.jpg';
    		$imagedata = $imageArray[$x];
    		$imagedata= str_replace('data:image/jpeg;base64,','', $imagedata);
    		$imagedata = str_replace('data:image/jpg;base64,','', $imagedata);
    		$imagedata = str_replace('','+', $imagedata);
    		$imagedata = base64_decode($imagedata);
    		file_put_contents($target_path, $imagedata);
    		$img[$x] = '/petbaopencart/api/'.$target_path;
    	}else{
    			$img[$x]='';
    		}
    	}
    	*/


			//check if this is necessary
	    	if($request->viral == null) {
	    		$request->viral = 'false';
	    	}
	    	if($request->anti_rabies == null) {
	    		$request->anti_rabies = 'false';
	    	}
	    	if($request->description == null) {
	    		$request->description = '';
	    	}
			//
			$adopt = Adopt::create([
		            'user_id' => $request->user_id,
		            'petFlag' => 1,
		            // 'img1' => $img[0],
		            // 'img2' => $img[1],
		            // 'img3' => $img[2],
		            // 'img4' => $img[3],
		            'name' => $request->name,
					'c_id' => $request->c_id,
		            'animal_typ' => $request->animal,  //check this
		            'gender' => $request->gender,
		            'dob' => $request->dob,
		            'breed' => $request->breed,
		            'color' => $request->color,
		            'anti_rbs' => $request->anti_rabies,
		            'viral' => $request->viral,
		            'note' => $request->description,
		            'city' => $request->city,
		            'longitude' => $request->long,
		            'latitude' => $request->lat,
		        ]);

			if($adopt) {
				return ['success' => true, 'message' =>'Successfully Added'];
			} else {
				return ['success' => false, 'message' =>'Wasn\'t able to be added'];
			}
		} else {
			return ['success' => false, 'message' => 'Login to continue'];
		}
	}


	public function get($id, Request $request)
	{
		
		$user_id = $request->user_id;
		if (!empty($user_id)) {
			$data = Adopt::where(['adopt_id' => $id, 'user_id' => $user_id])->with([
		    	'animal_typ',
		    	'color',
		    ])->firstOrFail();
			if(!empty($data)) {
				return ['success' => true, 'adopt' => $data];
			} else {
				return ['success' => false, 'message' => 'Don\'t have permission to edit'];
			}
		} else {
			return ['success' => false, 'message' => 'Login to continue'];
		}
	}


	public function update(Request $request)
	{
		$user_id = $request->user_id;
		if (!empty($user_id)) {	
			//make use of controllers in the front end & show the all the editable data in the textfields and update the changes
			$update = Adopt::where(['user_id' => $request->user_id, 'adopt_id' => $request->adopt_id])->update([
				'name' => $request->name,
		        'animal_typ' => $request->animal,  //check this
		        'gender' => $request->gender,
		        'dob' => $request->dob,
		        'breed' => $request->breed,
		        'color' => $request->color,
		        'anti_rbs' => $request->anti_rabies,
		        'viral' => $request->viral,
		        'note' => $request->description,
			]);

			if($update) {
				return ['success' => true, 'message' => 'Update successful'];
			} else {
				return ['success' => false, 'message' =>'Updation failed'];
			}
		} else {
			return ['success' => false, 'message' => 'Login to continue'];
		}
	}
	

	public function delete(Request $request)
	{
		$user_id = $request->user_id;
		// return $request->all();
		if (!empty($user_id)) {
			$delete = Adopt::where(['user_id' => $request->user_id, 'adopt_id' => $request->adopt_id])->delete();
			if($delete) {
				return ['success' => true, 'message' => 'Successfully deleted'];
			} else {
				return ['success' => false, 'message' => 'Deletion failed'];
			}
		} else {
			return ['success' => false, 'message' => 'Login to continue'];
		}
	}


	public function meet($id)
	{	
	    //
	    
	}

	public function wishlist_add(Request $request)
	{
		$user_id = $request->user_id;
		if(!empty($user_id)){
			$data = Wishlist::where('customer_id', $user_id)->where('adopt_id', $request->adopt_id)->first();
			//return $data;
			
			if($data == null){
				$data = Wishlist::create([
					'customer_id' => $user_id,
					'adopt_id' => $request->adopt_id,
					'date_added' => date("Y-m-d h:i:s"),
				]);
				return ['success' => true, 'message' => 'Added successfully'];
			}
			else {
				return ['success' => false, 'message' => 'Already present in db'];
			}
		} else {
			return response()->json([
                'success' => false,
                'message' => 'Login to continue',
            ], 401);
		}
	}

	public function wishlist_del(Request $request)
	{
		$user_id = $request->user_id;
		if(!empty($user_id)){
			$data = Wishlist::where('customer_id', $user_id)->where('adopt_id', $request->adopt_id)->delete();
			if($data == 1){
				return ['success' => true, 'message' => 'Deleted successfully'];
			}
			else {
				return ['success' => false, 'message' => 'Row not present so nothing to delete'];
			}
		} else {
			return response()->json([
                'success' => false,
                'message' => 'Login to continue',
            ], 401);
		}
	}
}
