<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterGroup extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oc_filter_group_description';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'filter_group_id';

    public function filter_options ()
    {
    	return $this->hasMany(Filter::class, 'filter_group_id');
    }
}
