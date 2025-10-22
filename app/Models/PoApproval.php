<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoApproval extends Model
{
    /** @use HasFactory<\Database\Factories\PoApprovalFactory> */
    use HasFactory;

    protected $fillable = [
        "approver_id",
        "status",
        'comments',
        'approved_at',
    ];


    public function order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }



}
