<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrders extends Model
{
    use HasFactory;

    protected $table = 'product_orders';

    protected $fillable = [
        'order_id',
        'product_id'
    ];
}
