<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Employer;
use App\Models\Supplier;

class PurchaseOrder extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseOrderFactory> */
    use HasFactory;

    public function employee()
    {
        return $this->belongsTo(Employer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

}
