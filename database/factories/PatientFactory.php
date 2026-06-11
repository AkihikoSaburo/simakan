<?php

namespace Database\Factories;

use App\Models\Bangsal;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        $firstNames = ['Andi', 'Baso', 'Tenri', 'Daeng', 'Syamsul', 'Rosdiana', 'Badawi', 'Nur', 'Alwi', 'Muchlis', 'Sitti', 'Baco', 'Mappaseling', 'Marlina', 'Fitriani', 'Kaharuddin', 'Amirullah', 'Asrul', 'Hasnah', 'Fatmawati'];
        $lastNames = ['Mapata', 'Pabotinggi', 'Pangerang', 'Mattalatta', 'Patittingi', 'Lantara', 'Bausepu', 'Matalatta', 'Soreang', 'Bachtiar', 'Siddik', 'Tadjuddin', 'Kalla', 'Yasin', 'Ghalib', 'Mandra', 'Nessa', 'Palaguna', 'Manning', 'Sanusi'];

        $nama = fake()->randomElement($firstNames) . ' ' . fake()->randomElement($lastNames);

        return [
            'bangsal_id' => Bangsal::inRandomOrder()->first()?->id ?? Bangsal::factory(),
            'no_rm' => fake()->unique()->numerify('##.##.##'),
            'nama' => $nama,
            'kamar' => fake()->randomElement(['Kamar ', 'VIP ', 'Kelas ']) . fake()->numberBetween(101, 108),
            'tanggal_lahir' => fake()->date('Y-m-d', '-10 years'),
        ];
    }
}
