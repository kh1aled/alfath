<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    /** @use HasFactory<\Database\Factories\SalesReturnFactory> */
    use HasFactory;


    protected $fillable = [
        'return_number',
        'invoice_id',
        'customer_id',
        'return_date',
        'total_amount',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    public function items()
    {
        return $this->hasMany(SalesReturnItem::class, 'return_id');
    }

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'invoice_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
