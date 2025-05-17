<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Transaction;
use App\Models\PurchaseOrder;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;


    public function storages()
    {
        return $this->belongsToMany(Storage::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }


    public function purchaseOrders()
    {
        return $this->belongsToMany(PurchaseOrder::class, 'purchase_order_products')
            ->withPivot('quantity', 'unit_price', 'total_price', 'note')
            ->withTimestamps();
    }
}
