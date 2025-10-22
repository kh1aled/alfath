<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisitionItems extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseRequisitionItemsFactory> */
    use HasFactory;
    protected $fillable = [
        'pr_id',
        'item_code',
        'description',
        'quantity',
        'unit',
        'estimated_price',
        'total_estimated',
        'notes',
    ];

    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class, 'pr_id');
    }
}
