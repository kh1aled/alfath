<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItems extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseOrderItemsFactory> */
    use HasFactory;

    protected $fillable = [
        'po_id',
        'quantity',
        'unit',
        'unit_price',
        'line_total',
        'notes',
        'description',
        'name',
        'product_id'
    ];




    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $year = date('Y');
            $lastOrderItem = self::whereYear('created_at', $year)->latest('id')->first();
            $nextId = $lastOrderItem ? $lastOrderItem->id + 1 : 1;
            $model->item_code = sprintf("ITEM-%s-%05d", $year, $nextId);
        });
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'product_id');
    }
}
