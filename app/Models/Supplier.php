<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory;
    protected $fillable = [
        "name",
        "address",
        "email",
        "city",
        "country",
        "zip_code",
    ];
    public function phone(): MorphOne
    {
        return $this->morphOne(Phone::class, 'phoneable');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }
}
