<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionValueDescription extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oc_option_value_description';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'option_value_id';
}
