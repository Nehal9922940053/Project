<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalType extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'animal';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'animal_id';

    
}
