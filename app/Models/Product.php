<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Storage;
use App\Models\Transaction;
use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'count',
        'minimum_quantity',
        'unit',
        'buying_price',
        'selling_price',
        'weight',
        'image',
        'status',
    ];

    public function storages()
    {
        return $this->belongsToMany(Storage::class , 'product_storage')
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function purchaseOrderItems(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItems::class , 'product_id');
    }
}
