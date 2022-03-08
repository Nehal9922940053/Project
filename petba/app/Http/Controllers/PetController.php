<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index(){
        $pets = Pet::all();
        return $pets;
    }


    public function store(Request $request){
        Pet::create($request->all());
        return "success";
    }
    

    public function update(Request $request){
        $pet = Pet::where('id', $request->id)->first();
        $pet->delete();
        return "success";
    }

}
