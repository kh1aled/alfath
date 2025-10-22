<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodReceipt extends Model
{
    /** @use HasFactory<\Database\Factories\GoodReceiptFactory> */
    use HasFactory;

    protected $fillable = [
        'po_id',
        'supplier_id',
        'receipt_date',
        'status',
        'invoice_image',
        'received_by'
    ];

    public function items()
    {
        return $this->hasMany(GoodReceiptItems::class , "good_receipt_id");
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }
}
