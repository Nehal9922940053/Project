<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionValue extends Model
{
    use HasFactory;

    protected $table = 'oc_product_option_value';

    protected $primaryKey = 'product_option_value_id';

    public function required()
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    public function option_category()
    {
        return $this->belongsTo(OptionDescription::class, 'option_id');
    }

    public function option_value()
    {
        return $this->belongsTo(OptionValueDescription::class, 'option_value_id');
    }

    
}
