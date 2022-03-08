<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'oc_product';

    protected $primaryKey = 'product_id';

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    public function product_description()
    {
        return $this->belongsTo(ProductDescription::class, 'product_id');
    }

    public function product_image()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function weight_class_id()
    {
        return $this->belongsTo(WeightClass::class, 'weight_class_id');
    }

    // public function product_option()
    // {
    //     return $this->hasMany(ProductOptionValue::class, 'product_id');
    // }

    public function product_option_category()
    {
        return $this->hasMany(ProductOption::class, 'product_id');
    }
}
