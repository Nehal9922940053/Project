<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oc_cart';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'cart_id';

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

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product_description()
    {
        return $this->belongsTo(ProductDescription::class, 'product_id');
    }
}
