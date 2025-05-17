<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Transaction;
use App\Models\Phone;
use App\Models\PurchaseOrder;
use App\Models\InventoryAudit;
class Employer extends Model
{
    /** @use HasFactory<\Database\Factories\EmployerFactory> */
    use HasFactory;


    public function phones()
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }

    public function transactions(): HasMany{
        return $this->hasMany(Transaction::class);
    }

    public function purchaseOrders(): HasMany{
        return $this->hasMany(PurchaseOrder::class);
    }

    public function inventorys(): HasMany{
        return $this->hasMany(InventoryAudit::class);
    }
    
}
