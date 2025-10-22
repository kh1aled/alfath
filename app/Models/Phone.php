<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Phone extends Model
{
    /** @use HasFactory<\Database\Factories\PhoneFactory> */
    use HasFactory;

    protected $fillable = ["phone_number"];

    public function phoneable(): MorphTo
    {
        return $this->morphTo();
    }
}
