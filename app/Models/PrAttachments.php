<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PrAttachments extends Model
{
    /** @use HasFactory<\Database\Factories\PrAttachmentsFactory> */
    use HasFactory;
    protected $fillable = [
        'pr_id',
        'file_path',
        'file_type',
        'uploaded_by',
        'uploaded_at',
    ];
    protected $appends = ['file_url'];

    public function requisition()
    {
        return $this->belongsTo(PurchaseRequisition::class, 'pr_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }



    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
