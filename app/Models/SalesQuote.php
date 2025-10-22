<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesQuote extends Model
{
    /** @use HasFactory<\Database\Factories\SalesQuoteFactory> */
     use HasFactory, SoftDeletes;

    protected $table = 'sales_quotes';

    protected $fillable = [
        'reference', 'customer_id', 'status', 'valid_until',
        'subtotal', 'discount', 'tax', 'total', 'created_by', 'meta'
    ];

    protected $casts = [
        'valid_until' => 'date',
        'meta' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(SalesQuoteItem::class, 'quote_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
