<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'oc_address';

    protected $primaryKey = 'address_id';

    public $timestamps = false;

    protected $guarded = [];

    public function zone()
    {
    	return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function country()
    {
    	return $this->belongsTo(Country::class, 'country_id');
    }
}
