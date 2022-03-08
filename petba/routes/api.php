<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\AdoptController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VetController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PetController;
use App\Models\Country;
use App\Models\Zone;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::get('pets', [PetController::class, 'index']);


Route::post('pets', [PetController::class, 'store']);
Route::put('pets/{id}', [PetController::class, 'update']);



Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/password_reset', [UserController::class, 'passwordReset']);  
Route::post('/update', [UserController::class, 'update']);                           //TODO
Route::post('/send_otp', [OtpController::class, 'sendOTP']);                         // THE SEND AND RESEND OTP WILL USE THE SAME api
Route::prefix('address')->name('address/')->group(static function() {
	Route::get('/', [UserController::class, 'address_get']);                         //TO GET ADDRESSES
	Route::post('/', [UserController::class, 'address_add']);                        //TO ADD AN ADDRESS
	Route::get('/{id}', [UserController::class, 'address_individual']);              //TO GET INDIVIDUAL ADDRESS
	Route::get('/edit/{id}', [UserController::class, 'address_edit']);               //TO EDIT AN EXISTING ADDRESS
});
Route::post('/check_otp', [OtpController::class, 'checkOTP']);
Route::post('/forgot_password', [OtpController::class, 'forgotPassword']);
Route::get('/user_details', [UserController::class, 'user_details_get']);
Route::post('/user_details', [UserController::class, 'user_details_post']);

Route::get('/users', [UserController::class, 'users']);                              //TO DISPLAY ALL THE USERS


Route::prefix('dashboard')->name('dashboard/')->group(static function() {
	Route::get('/adoption', [HomeController::class, 'adoption']);
	Route::get('/most_selling', [HomeController::class, 'most']);
	Route::get('/latest_selling', [HomeController::class, 'latest']);
});


Route::prefix('adopt')->name('adopt/')->group(static function() {
	Route::get('/', [AdoptController::class, 'index']);                              //SHOW THE LIST OF ALL THE PETS AVAILABLE
	Route::get('/read/{id}', [AdoptController::class, 'read']);                      //INDIVIDUAL PET
	Route::post('/store', [AdoptController::class, 'store']);                        //TO ADD THE PETS
	Route::get('/edit/{id}', [AdoptController::class, 'get']);                       //TO GET THE DETAILS OF THE PET TO EDIT
	Route::post('/edit', [AdoptController::class, 'update']);                        //TO UPDATE THE CHANGES TO THE DB
	Route::post('/delete', [AdoptController::class, 'delete']);                      //TO REMOVE A PET FROM THE DB
	Route::post('/adopted', [AdoptController::class, 'adopted']);                    //WHEN THE PET GETS ADOPTED
	Route::get('/meet/{id}', [AdoptController::class, 'meet']);                      //MEET WILL BASICALLY BE TO SHOW THE CHAT SCREEN
	Route::post('/wishlist/add', [AdoptController::class, 'wishlist_add']);          //TO ADD TO WISHLIST
	Route::post('/wishlist/remove', [AdoptController::class, 'wishlist_del']);       //TO REMOVE TO WISHLIST
});


Route::prefix('product')->name('product/')->group(static function() {
	Route::get('/', [ProductController::class, 'index']);                            //SHOW LIST OF ALL PRODUCTS
	Route::get('/filter/{id}', [ProductController::class, 'filter']);
	Route::get('/filter_group', [ProductController::class, 'filter_group']);
	Route::get('/read/{id}', [ProductController::class, 'read']);                    //INDIVIDUAL PRODUCT
	Route::post('/wishlist/add', [ProductController::class, 'wishlist_add']);        //ADD TO WISHLIST
	Route::post('/wishlist/remove', [ProductController::class, 'wishlist_remove']);  //REMOVE FROM WISHLIST
});

Route::prefix('vet')->name('vet/')->group(static function() {
	Route::get('/', [VetController::class, 'index']);
	Route::get('/{lat}/{long}/{rad}', [VetController::class, 'vet_nearby']);        //TO GET ALL THE NEARBY VETS
});

Route::prefix('cart')->name('cart/')->group(static function() {
	Route::get('/', [CartController::class, 'index']);                               //TO VIEW CART
	Route::post('/add', [CartController::class, 'store']);                           //TO ADD ITEM TO CART
	Route::post('/quantity', [CartController::class, 'quantity']);                   //CHANGE IN QUANTITY
	Route::post('/remove', [CartController::class, 'remove']);                       //TO REMOVE ITEM ADDED TO CART
});


Route::prefix('order')->name('order/')->group(static function() {
	Route::get('/',[OrderController::class, 'index']);
	Route::post('/',[OrderController::class, 'store']);
	Route::get('/track',[OrderController::class, 'track']);
});


Route::get('/country', function() {return Country::all();});
Route::get('/zone/{id}', function($id) {return Zone::where('country_id', $id)->get();});





Route::get('/test', function() {
	$optionsList = array();
	for ($i=0;$i<5;$i++){
		$j=$i+1;
		$optionsList += [ "element".$j => $j ];
	}
	$optionsList = json_encode($optionsList);
	return $optionsList;

});
