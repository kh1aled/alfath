<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    /** @use HasFactory<\Database\Factories\SalesOrderFactory> */
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'quote_id',
        'created_by',
        'updated_by',
        'order_date',
        'status',
        'total_amount',
        'notes'
    ];

    public function items()
    {
        return $this->hasMany(SalesOrderItem::class, 'order_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function quote()
    {
        return $this->belongsTo(SalesQuote::class, 'quote_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
