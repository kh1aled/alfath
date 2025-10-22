<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product;

class Storage extends Model
{
    /** @use HasFactory<\Database\Factories\StorageFactory> */
    use HasFactory;



    public function products()
    {
        return $this->belongsToMany(Product::class , 'product_storage')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function transfersOut()
    {
        return $this->hasMany(Transfer::class, 'from_warehouse_id');
    }

    public function transfersIn()
    {
        return $this->hasMany(Transfer::class, 'to_warehouse_id');
    }
}
