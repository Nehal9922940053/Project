<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adopt extends Model
{
    use HasFactory;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'adopt';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'adopt_id';

    /**
     * Indicates if the model should be timestamped
     *
     * @var array
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
    ];

    public function animal_typ()
    {
        return $this->belongsTo(AnimalType::class, 'animal_typ');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'c_id');
    }

    public function breed()
    {
        return $this->belongsTo(Breed::class, 'breed');
    }



}
