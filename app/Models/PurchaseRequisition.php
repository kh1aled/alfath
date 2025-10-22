<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequisition extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseRequisitionFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'requester_id',
        'priority',
        'needed_by_date',
        'purpose',
        'status',
        'approved_by',
        'approved_at',
        'notes',
        'attachments',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseRequisitionItems::class, 'pr_id');
    }

    public function approvals()
    {
        return $this->hasMany(PrApproval::class, 'pr_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function attachments()
    {
        return $this->hasMany(PrAttachments::class, 'pr_id');
    }
}
