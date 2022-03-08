<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;

    protected $table = 'oc_product_option';

    protected $primaryKey = 'product_option_id';

    public function product_category_name()
    {
        return $this->belongsTo(OptionDescription::class, 'option_id');
    }

    public function product_options()
    {
        return $this->hasMany(ProductOptionValue::class, 'product_option_id');
    }
}
