<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\JadwalKuliah;
use App\Models\PengampuMataKuliah; 
use App\Models\NilaiMahasiswa;

class DashboardController extends Controller
{
    public function mahasiswaDashboard()
    {
        return view('mahasiswa.dashboard');
    }

    public function dosenDashboard()
    {
        return view('dosen.dashboard');
    }

    public function adminDashboard()
    {
        $totalUsers = User::count();
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $totalMataKuliah = MataKuliah::count();

        $dataSummary = [
            'Dosen' => [
                'count' => Dosen::count(),
                'last_updated' => Dosen::max('updated_at') 
            ],
            'Mata Kuliah' => [
                'count' => MataKuliah::count(),
                'last_updated' => MataKuliah::max('updated_at')
            ],
            'Mahasiswa' => [
                'count' => Mahasiswa::count(),
                'last_updated' => Mahasiswa::max('updated_at')
            ],
            'Jadwal Mengajar' => [
                'count' => PengampuMataKuliah::count(),
                'last_updated' => PengampuMataKuliah::max('updated_at')
            ],
            'Nilai Mahasiswa' => [
                'count' => NilaiMahasiswa::count(),
                'last_updated' => NilaiMahasiswa::max('updated_at')
            ],
            
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalMahasiswa',
            'totalDosen',
            'totalMataKuliah',
            'dataSummary'
        ));
    }

    public function kelolaJadwal()
    {
        return view('admin.kelola_jadwal');
    }


    public function kelolaDataDosen()
    {
        return view('admin.kelola_data_dosen');
    }

    public function kelolaDataMataKuliah()
    {
        return view('admin.kelola_data_mata_kuliah');
    }

    public function kelolaNilaiMahasiswaAdmin()
    {
        return view('admin.kelola_nilai_mahasiswa');
    }


    public function kelolaJadwalMengajar()
    {
        return view('admin.kelola_jadwal_mengajar');
    }

    public function kelolaJadwalKuliah()
    {
        return view('admin.kelola_jadwal_kuliah');
    }
}