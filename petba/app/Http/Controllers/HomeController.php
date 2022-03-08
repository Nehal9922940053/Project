<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adopt;

class HomeController extends Controller
{
    public function adoption()
	{
	    $adoptions =  Adopt::with([
		    	// 'animal_typ',
		    	// 'breed',
				// 'adopt' => function($query){
				// 	$query->select('img1');
				// },
		    	// 'user'=> function($query){
		    	// 	$query->select('customer_id');
		    	// },
	    	])->get()->take(5);
	    //adoption model need wishlist so just made the entire thing false as it is not required in the output
	    // foreach ($adoptions as $key => $adoption) {
		// 	$adoption['wishlist'] = false;
		// }

		return $adoptions;
	}



	public function most()
	{
	    //
	}


	
	public function latest()
	{
	    //
	}
}
