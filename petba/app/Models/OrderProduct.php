<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'oc_order_product';

    protected $primaryKey = 'order_product_id';

    public $timestamps = false;

    protected $guarded = [];

    public function product_image() 
    {
    	return $this->belongsTo(Product::class, 'product_id');
    }
}
