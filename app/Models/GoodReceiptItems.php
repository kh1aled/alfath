<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodReceiptItems extends Model
{
    /** @use HasFactory<\Database\Factories\GoodReceiptItemsFactory> */
    use HasFactory;


    protected $fillable = [
        'goods_receipt_id',
        'item_id',
        'received_qty',
        'good_receipt_id',
        'ordered_qty'
    ];

    public function receipt()
    {
        return $this->belongsTo(GoodReceipt::class , "good_receipt_id");
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }
}
