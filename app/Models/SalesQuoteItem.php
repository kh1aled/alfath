<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesQuoteItem extends Model
{
    /** @use HasFactory<\Database\Factories\SalesQuoteItemFactory> */
    use HasFactory;

    protected $table = 'sales_quote_items';

    protected $fillable = [
        'quote_id', 'product_id', 'qty', 'unit_price', 'discount', 'tax_amount', 'line_total', 'meta'
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function quote()
    {
        return $this->belongsTo(SalesQuote::class, 'quote_id');
    }
}
