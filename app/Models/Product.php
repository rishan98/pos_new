<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'barcode',
        'price',
        'quantity',
        'status',
        'minimum_quantity',
        'discount_type',
        'discount'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function getImageUrl()
    {
        if ($this->image) {
            return asset($this->image);
        }
        return asset('images/img-placeholder.jpg');
    }

    public function inventory() {
        return $this->hasOne(ProductInventory::class);
    }
}
