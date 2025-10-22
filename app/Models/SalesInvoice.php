<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    /** @use HasFactory<\Database\Factories\SalesInvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'order_id',
        'customer_id',
        'created_by',
        'updated_by',
        'invoice_date',
        'due_date',
        'status',
        'total_amount',
        'paid_amount',
        'notes'
    ];

    public function items()
    {
        return $this->hasMany(SalesInvoiceItem::class, 'invoice_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
