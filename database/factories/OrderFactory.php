<?php

namespace Database\Factories;

use App\Models\Bangsal;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $bangsalId = Bangsal::inRandomOrder()->first()?->id ?? Bangsal::factory();
        $user = User::where('role', 'bangsal')->inRandomOrder()->first();
    
        return [
            'bangsal_id' => $bangsalId,
            'created_by' => $user ? $user->id : User::factory(['role' => 'bangsal']),
            'tanggal_pesanan' => fake()->boolean(20)
                ? now()->format('Y-m-d')
                : fake()->dateTimeBetween('-30 days', '-1 day')->format('Y-m-d'),
        ];
    }
}
