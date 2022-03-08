<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderOption extends Model
{
    use HasFactory;

    protected $table = 'oc_order_option';

    protected $primaryKey = 'order_option_id';

    public $timestamps = false;

    protected $guarded = [];
}
