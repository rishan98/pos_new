<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInventory extends Model
{
    protected $fillable = [
        'product_id',
        'master_quantity',
        'reserved_quantity',
        'removed_quantity',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function history()
    {
        return $this->hasMany(ProductInventoryHistory::class);
    }
}
