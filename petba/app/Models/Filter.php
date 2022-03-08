<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oc_filter_description';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'filter_id';
}
