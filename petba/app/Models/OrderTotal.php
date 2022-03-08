<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTotal extends Model
{
    use HasFactory;

    protected $table = 'oc_order_total';

    protected $primaryKey = 'order_total_id';

    public $timestamps = false;

    protected $guarded = [];
}
