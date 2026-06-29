<?php

namespace Database\Seeders;

use App\Models\Bangsal;
use App\Models\User;
use App\Models\Patient;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SuperAdminSeeder::class);

        if (App::environment('local', 'testing')) {
            $this->call(BangsalSeeder::class);

            $this->call(UserSeeder::class);

            $bangsals = Bangsal::all();
            foreach ($bangsals as $bangsal) {
                Patient::factory()->count(4)->create([
                    'bangsal_id' => $bangsal->id,
                ]);
            }
            Patient::factory()->count(10)->create([
                'bangsal_id' => fn() => $bangsals->random()->id,
            ]);

            $bangsalUsers = User::where('role', 'bangsal')->get();

            if ($bangsalUsers->isNotEmpty()) {
                for ($i = 0; $i < 20; $i++) {
                    $randomUser = $bangsalUsers->random();

                    Order::factory()->create([
                        'created_by' => $randomUser->id,
                        'bangsal_id' => $randomUser->bangsal_id,
                    ]);
                }
            }

            $orders = Order::all();
            foreach ($orders as $order) {
                $patients = Patient::where('bangsal_id', $order->bangsal_id)->get();
                $count = fake()->numberBetween(2, min(6, $patients->count()));

                $selectedPatients = $patients->random($count);

                foreach ($selectedPatients as $patient) {
                    OrderDetail::factory()->create([
                        'order_id' => $order->id,
                        'patient_id' => $patient->id,
                    ]);
                }
            }
        }
    }
}
