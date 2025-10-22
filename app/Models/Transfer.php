<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Pest\Laravel\json;

class Transfer extends Model
{
    /** @use HasFactory<\Database\Factories\TransferFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'from_storage_id',
        'to_storage_id',
        'created_by',
        'authorized_by',
        'status',
        'notes',
        'reason',
        'reference',
        'date',
    ];

    public function getFromWarehouseIdAttribute($value)
    {

        $warhouse = Storage::findOrFail($value);
        return $warhouse;
    }

    public function getToWarehouseIdAttribute($value)
    {

        $warhouse = Storage::findOrFail($value);
        return $warhouse;
    }



    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function fromStorage()
    {
        return $this->belongsTo(Storage::class, 'from_storage_id');
    }
    public function toStorage()
    {
        return $this->belongsTo(Storage::class, 'to_storage_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function authorizer()
    {
        return $this->belongsTo(User::class, 'authorized_by');
    }
}
