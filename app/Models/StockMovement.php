<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    /** @use HasFactory<\Database\Factories\StockMovementFactory> */
    use HasFactory;

      protected $fillable = [
        'product_id',
        'storage_id',
        'quantity',
        'movement_type',
        'reference_id',
        'reference_type',
        'notes',
        'created_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
