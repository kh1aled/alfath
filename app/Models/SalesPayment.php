<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPayment extends Model
{
    /** @use HasFactory<\Database\Factories\SalesPaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'invoice_id',
        'customer_id',
        'amount',
        'payment_method',
        'reference',
        'payment_date',
        'notes',
        'created_by',
        'updated_by',
    ];

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
