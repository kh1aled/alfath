<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseOrderFactory> */
    use HasFactory;

    protected $fillable = [
        'pr_id',
        'supplier_id',
        'order_date',
        'status',
        'currency',
        'payment_terms',
        'tax',
        'discount',
        'total_amount',
        'created_by',
        'approved_by',

    ];


    public function items()
    {
        return $this->hasMany(PurchaseOrderItems::class, 'po_id', 'id');
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $year = date('Y');
            $lastOrder = self::whereYear('created_at', $year)->latest('id')->first();
            $nextId = $lastOrder ? $lastOrder->id + 1 : 1;
            $model->po_number = sprintf("PO-%s-%05d", $year, $nextId);
        });
    }

    public function approvals()
    {
        return $this->hasMany(PoApproval::class, 'po_id');
    }

    public function requisition()
    {
        return $this->belongsTo(PurchaseRequisition::class, 'pr_id');
    }
}
