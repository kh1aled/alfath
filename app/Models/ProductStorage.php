<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductStorage extends Model
{
    /** @use HasFactory<\Database\Factories\ProductStorageFactory> */
    use HasFactory;

    //
    protected $table = 'product_storage';
    protected $fillable = ['product_id' , 'storage_id' , 'quantity'];
}
