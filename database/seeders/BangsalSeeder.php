<?php

namespace Database\Seeders;

use App\Models\Bangsal;
use Illuminate\Database\Seeder;

class BangsalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bangsals = [
            'Mawar',
            'Melati',
            'Anggrek',
            'Anggrek 2',
            'Bougenville',
        ];

        foreach ($bangsals as $nama) {
            Bangsal::create(['nama_bangsal' => $nama]);
        }
    }
}
