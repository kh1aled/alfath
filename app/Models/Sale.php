<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory;

    protected $fillable = [
        'sale_number',
        'customer_id',
        'warehouse_id',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_method',
        'status',
        'sale_date',
        'created_by',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
