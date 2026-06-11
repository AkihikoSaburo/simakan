<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'bangsal_id',
        'no_rm',
        'nama',
        'kamar',
        'tanggal_lahir',
    ];

    public function bangsal(): BelongsTo
    {
        return $this->belongsTo(Bangsal::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
}
