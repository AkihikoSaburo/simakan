<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderDetailFactory extends Factory
{
    protected $model = OrderDetail::class;

    public function definition(): array
    {
        $nasi = fake()->boolean(50);
        $bubur = fake()->boolean(20);
        $makanan_cair = fake()->boolean(10);
        $bs = fake()->boolean(15);
        $sonde = fake()->boolean(10);

        if (!$nasi && !$bubur && !$makanan_cair && !$bs && !$sonde) {
            $nasi = true;
        }

        $activeFoodsCount = ($nasi ? 1 : 0) + ($bubur ? 1 : 0) + ($makanan_cair ? 1 : 0) + ($bs ? 1 : 0) + ($sonde ? 1 : 0);
        
        $diet = null;
        $keterangan = null;

        if ($activeFoodsCount > 1) {
            if ($sonde) {
                $diet = 'Sonde 100cc + Susu 2x1';
                $keterangan = 'Pemberian via sonde disertai diet khusus';
            } else {
                $diets = ['Diabetes', 'Rendah Garam', 'Tinggi Protein', 'Pasca Operasi'];
                $diet = fake()->randomElement($diets);
                $keterangan = 'Kombinasi makanan untuk diet ' . strtolower($diet);
            }
        } else {
            if ($sonde) {
                $diet = 'Sonde 100cc + Susu 2x1';
                $keterangan = 'Pemberian via sonde';
            } else {
                $diets = [null, 'Diabetes', 'Rendah Garam', 'Tinggi Protein', 'Pasca Operasi'];
                $diet = fake()->randomElement($diets);
                if ($diet) {
                    $keterangan = 'Diet ' . strtolower($diet);
                }
            }
        }

        return [
            'order_id' => Order::factory(),
            'patient_id' => Patient::factory(),
            'nasi' => $nasi,
            'bubur' => $bubur,
            'makanan_cair' => $makanan_cair,
            'bs' => $bs,
            'sonde' => $sonde,
            'diet_pasien' => $diet,
            'keterangan' => $keterangan,
        ];
    }
}
