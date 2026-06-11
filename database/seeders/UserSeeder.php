<?php

namespace Database\Seeders;

use App\Models\Bangsal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        User::create([
            'username' => 'superadmin',
            'role' => 'superadmin',
            'password' => $password,
        ]);

        User::create([
            'username' => 'admin',
            'role' => 'admin',
            'password' => $password,
        ]);

        User::create([
            'username' => 'dapur',
            'role' => 'dapur',
            'password' => $password,
        ]);

        $bangsalMapping = [
            'mawar' => 'Mawar',
            'melati' => 'Melati',
            'anggrek' => 'Anggrek',
            'anggrek2' => 'Anggrek 2',
            'bougenville' => 'Bougenville',
        ];

        foreach ($bangsalMapping as $username => $bangsalNama) {
            $bangsal = Bangsal::where('nama_bangsal', $bangsalNama)->first();
            
            User::create([
                'username' => $username,
                'role' => 'bangsal',
                'bangsal_id' => $bangsal?->id,
                'password' => $password,
            ]);
        }
    }
}
