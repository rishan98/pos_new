<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInventoryHistory extends Model
{
    protected $fillable = [
        'product_id',
        'product_inventory_id',
        'running_quantity',
        'quantity',
        'order_id',
        'operation'
    ];
}
