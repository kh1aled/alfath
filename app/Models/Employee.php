<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Transaction;
use App\Models\Phone;
use App\Models\PurchaseOrder;
use App\Models\InventoryAudit;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'status',
        'address',
        'date_of_birth',
        'hire_date',
        'salary',
        'photo',
    ];
    public function phone()
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function inventorys(): HasMany
    {
        return $this->hasMany(InventoryAudit::class);
    }
}
