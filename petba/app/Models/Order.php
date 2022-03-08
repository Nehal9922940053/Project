<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'oc_order';

    protected $primaryKey = 'order_id';

    public $timestamps = false;

    protected $guarded = [];

    public function order_history()
    {
        return $this->belongsTo(OrderHistory::class, 'order_id');
    }

    public function order_products()
    {
        return $this->hasMany(OrderProduct::class, 'order_id');
    }
}
