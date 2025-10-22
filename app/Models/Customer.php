<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
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
}


//   public function phones()
//     {
//         return $this->morphMany(Phone::class, 'phoneable');
//     }

//     public function purchaseOrders()
//     {
//         return $this->hasMany(PurchaseOrder::class);
//     }