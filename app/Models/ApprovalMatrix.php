<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalMatrix extends Model
{
    /** @use HasFactory<\Database\Factories\ApprovalMatrixFactory> */
    use HasFactory;

    protected $fillable = [
        'approver_id',
        'level',
        'min_amount',
        'max_amount',
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
