<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderItem extends Model
{
    /** @use HasFactory<\Database\Factories\SalesOrderItemFactory> */
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'unit_price', 'total_price'];

    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
