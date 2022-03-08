<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vet;

class VetController extends Controller
{
    public function index () {
        $vets = Vet::all();
        return ['success' => true, 'vets' => $vets];
    }

    public function vet_nearby ($lat, $long, $rad) {

        $vets = Vet::where('latitude', '>', $lat-$rad)
        ->where('latitude', '<', $lat+$rad)
        ->where('longitude', '>', $long-$rad)
        ->where('longitude', '<', $long+$rad)
        ->get();

        if(!empty($vets[0])) {
            return ['success' => true, 'vets' => $vets];
        }
        return ['success' => false, 'message' => 'No vets available nearby'];
    }
}
