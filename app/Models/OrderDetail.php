<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'patient_id',
        'nasi',
        'bubur',
        'makanan_cair',
        'bs',
        'sonde',
        'diet_pasien',
        'keterangan',
    ];

    protected $casts = [
        'nasi' => 'boolean',
        'bubur' => 'boolean',
        'makanan_cair' => 'boolean',
        'bs' => 'boolean',
        'sonde' => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
