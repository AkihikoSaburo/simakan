<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'bangsal_id',
        'created_by',
        'tanggal_pesanan',
    ];

    protected $casts = [
        'tanggal_pesanan' => 'date',
    ];

    public function bangsal(): BelongsTo
    {
        return $this->belongsTo(Bangsal::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function getNasiCountAttribute(): int
    {
        return $this->orderDetails->where('nasi', true)->count();
    }

    public function getBuburCountAttribute(): int
    {
        return $this->orderDetails->where('bubur', true)->count();
    }

    public function getMakananCairCountAttribute(): int
    {
        return $this->orderDetails->where('makanan_cair', true)->count();
    }

    public function getBsCountAttribute(): int
    {
        return $this->orderDetails->where('bs', true)->count();
    }

    public function getSondeCountAttribute(): int
    {
        return $this->orderDetails->where('sonde', true)->count();
    }

    public function dashboard()
    {
        $orders = Order::with([
            'bangsal',
            'orderDetails.patient'
        ])
        ->whereDate('tanggal_pesanan', today())
        ->latest()
        ->get();
    
        return view('dapur.dashboard', compact('orders'));
    }
    
}
