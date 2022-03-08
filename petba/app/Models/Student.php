<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'oc_student';

    protected $primaryKey = 'order_history_id';

    public $timestamps = false;

    protected $guarded = [];
}
