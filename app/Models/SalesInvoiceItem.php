<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoiceItem extends Model
{
    /** @use HasFactory<\Database\Factories\SalesInvoiceItemFactory> */
    use HasFactory;

    protected $fillable = ['invoice_id', 'product_id', 'quantity', 'unit_price', 'total_price'];

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
