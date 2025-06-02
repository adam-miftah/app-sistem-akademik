<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mahasiswa; // Import model Mahasiswa
use App\Models\Dosen;     // Import model Dosen

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // 1. Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'), // password
            'role' => 'admin',
        ]);

        // 2. Create Dosen User and link to Dosen table
        $dosenUser = User::create([
            'name' => 'Dosen User',
            'email' => 'dosen@gmail.com',
            'password' => Hash::make('password123'), // password
            'role' => 'dosen',
        ]);
        // Link Dosen User to a Dosen entry
        Dosen::create([
            'user_id' => $dosenUser->id, 
            'nama' => 'Dosen User',
            'email' => 'dosen@gmail.com',
            'nidn' => '1234567890',
            'prodi' => 'Teknik Informatika',
        ]);

        // 3. Create Mahasiswa User and link to Mahasiswa table
        $mahasiswaUser = User::create([
            'name' => 'Mahasiswa User',
            'email' => 'mahasiswa@gmail.com',
            'password' => Hash::make('password123'), // password
            'role' => 'mahasiswa',
        ]);
        // Link Mahasiswa User to a Mahasiswa entry
        Mahasiswa::create([
            'user_id' => $mahasiswaUser->id, 
            'nim' => '221011400961', 
            'nama' => 'Adam Miftahul Falah',
            'jurusan' => 'Teknik Informatika',
            'angkatan' => '2022',
            'email' => 'mahasiswa@gmail.com',
            'telepon' => '081319310355',
            'alamat' => 'Jl. Contoh Alamat No. 123',
        ]);
    }
}