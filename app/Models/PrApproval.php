<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrApproval extends Model
{
    /** @use HasFactory<\Database\Factories\PrApprovalFactory> */
    use HasFactory;

    protected $fillable = ['pr_id',  'approver_id', 'status', 'comments', 'approved_at'];

    public function requisition()
    {
        return $this->belongsTo(PurchaseRequisition::class, 'pr_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
