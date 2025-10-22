<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturnItem extends Model
{
    /** @use HasFactory<\Database\Factories\SalesReturnItemFactory> */
    use HasFactory;

    protected $fillable = [
        'return_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
        'reason',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class, 'return_id');
    }
}
