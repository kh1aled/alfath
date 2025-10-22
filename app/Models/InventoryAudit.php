<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryAudit extends Model
{
    /** @use HasFactory<\Database\Factories\InventoryAuditFactory> */
    use HasFactory;

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }
}
