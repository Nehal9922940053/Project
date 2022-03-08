<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vet extends Model
{

    protected $table = 'vets';

    protected $primaryKey = 'id';


    public $timestamps = false;
    protected $guarded = [];
    
    
    protected $dates = [];
    
    protected $appends = ['resource_url'];

    /* ************************ ACCESSOR ************************* */

    public function getResourceUrlAttribute()
    {
        return url('/admin/vets/'.$this->getKey());
    }
}
