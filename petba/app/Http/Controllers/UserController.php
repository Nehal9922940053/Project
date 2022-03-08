<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    //TO DISPLAY ALL USERS
    public function users() {
		$users = User::all();
		return $users;
	}



	//REGISTER A NEW USER
    public function register(Request $request)
    {
        //to validate weather the fields are not empty/filled correctly
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:20',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'phone' => 'required|max:13', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors(),
            ], 200);
        }
        //to check if the email is available
        $user = User::where('email', $request->email)->first();
        if($user==null) {

        	$salt = substr(md5(microtime()),rand(0,26),9);
        	$password = sha1($salt . sha1($salt . sha1($request->password)));

            $time = now()->format('Y-m-d H:i:s');
        	User::create([
	            'firstname' => $request->name,
	            'telephone' => $request->phone,
	            'email' => $request->email,
	            'password' => $password,
	            'salt' => $salt,
	            'date_added' => $time,
	        ]);
	        return ['success' => true];
        }
        else {
        	return response()->json([
                'success' => false,
                'error' => 'Email previously registered',
            ], 401);
        }
    }



    //TO LOGIN A USER
    public function login (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors(),
            ], 200);
        }

        $data = User::where('email', $request->email)->select('salt')->first(); 
        $salt = $data->salt;     
        $password = sha1($salt . sha1($salt . sha1($request->password)));
        $user = User::where('email', $request->email)->where('password', $password)->first();

        if($user!=null){ 
            return ['success' => true, 'user' => $user];
        } 
        else{ 
            return response()->json([
                'success' => false,
                'error'=>'Invalid Credentials',
            ], 401); 
        }
        
    }



    //TO LOGOUT A USER
    public function logout(Request $request)
	{
	    //
	    return ['success' => true];
	}



	//IF THE USER WANTS TO CHANGE HIS PASSWORD
	public function passwordReset (Request $request)
	{
		//first I'm checking if the credentials match, then validate the password, then update the password using the email_id
		$data =  User::where('email', $request->email)->select('salt')->first(); 
        $salt = $data->salt;       
        $password = sha1($salt . sha1($salt . sha1($request->password)));
        $user = User::where('email', $request->email)->where('password', $password)->first();

        if($user!=null){ 

			$validator = Validator::make($request->all(), [
	            'new_password' => 'required|min:5',
	            'c_password' => 'required|same:new_password', 
	        ]); 

	        if ($validator->fails()) {
	            return response()->json([
	                'success' => false,
	                'error' => $validator->errors(),
	            ], 200);
	        }

	        $salt = substr(md5(microtime()),rand(0,26),9);
        	$password = sha1($salt . sha1($salt . sha1($request->new_password)));
            User::where('email', $request->email)->update(['password' => $password, 'salt' => $salt]);
            return ['success' => true];
        } 
        else{ 
            return response()->json([
                'success' => false,
                'error'=>'Invalid Password',
            ], 401); 
        }
    }


    //TO GET ALL THE USER ADRESSES
    public function address_get(Request $request)
    {
        $user_id = $request->user_id;
        // $user_id = 38;
        // return $request;
        if(!empty($user_id)){
            $address = Address::where('customer_id', $user_id)->with([
                'zone' => function ($query) {
                    $query->select('zone_id', 'name');
                },
                'country' => function ($query) {
                    $query->select('country_id', 'name');
                }
            ])->get();
            if (!empty($address[0])) {
                return ['success' => true, 'address' => $address];
            } else {
                return ['success' => false, 'message' => 'No address available'];
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Login to continue',
            ], 401);
        }
    }


    //TO INPUT NEW ADDRESS
    public function address_add(Request $request)
    {
        $user_id = $request->user_id;
        // return $request->all();
        $country_id = 99;
        if(!empty($user_id)){
            $address = Address::create([
                'customer_id' => $user_id,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'postcode' => $request->postcode,
                'country_id' => $country_id,
                'zone_id' => $request->zone_id,
            ]);
            if($address) {
                return ['success' => true, 'message' => 'Successfully added address'];
            } else {
                return ['success' => false, 'message' => 'Error: Didn\'t add address to the address table'];
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Login to continue',
            ], 401);
        }
    }

    public function address_edit(Request $request, $id)
    {
        $user_id = $request->query('user_id');
        if(!empty($user_id)){
            $address = Address::where(['address_id' => $id, 'customer_id' => $user_id])->with([
                'zone' => function ($query) {
                    $query->select('zone_id', 'name');
                },
                'country' => function ($query) {
                    $query->select('country_id', 'name');
                }
            ])->first();
            if(!empty($address)) {
                return ['success' => true, 'address' => $address];
            } else {
                return ['success' => false, 'message' => 'Error: Didn\'t find address'];
            }
        } else {    
            return response()->json([
                'success' => false,
                'message' => 'Login to continue',
            ], 401);
        }
    }

    public function address_individual(Request $request, $id)
    {
        $user_id = $request->query('user_id');
        if(!empty($user_id)){
            $address = Address::where(['address_id' => $id, 'customer_id' => $user_id])->with([
                'zone' => function ($query) {
                    $query->select('zone_id', 'name');
                },
                'country' => function ($query) {
                    $query->select('country_id', 'name');
                }
            ])->first();
            if(!empty($address)) {
                return ['success' => true, 'address' => $address];
            } else {
                return ['success' => false, 'message' => 'Error: Didn\'t find address'];
            }
        } else {    
            return response()->json([
                'success' => false,
                'message' => 'Login to continue',
            ], 401);
        }
    }

    public function user_details_get(Request $request)
    {
        $user_id = $request->query('user_id');
        return $request->all();
        if(!empty($user_id)){
            return User::where('customer_id', $user_id)->first();
        } else {    
            //return User::where('customer_id', $user_id)->first();
            return response()->json([
                'success' => false,
                'message' => 'Login to continue',
            ], 401);
        }
        
    }

    public function user_details_post(Request $request)
    {
        $user_id = $request->query('user_id');
        if(!empty($user_id)){
            $user = User::where('customer_id', $user_id)->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
            ]);
            if($user) {
                return ['success' => true, 'message' => 'Updation Succeded'];
            } else {
                return ['success' => false, 'message' => 'Updation Failed'];
            }
        } else {    
            return response()->json([
                'success' => false,
                'message' => 'Login to continue',
            ], 401);
        }
    }
}
