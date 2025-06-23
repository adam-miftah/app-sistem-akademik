<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\JadwalKuliah;
use App\Models\PengampuMataKuliah;
use App\Models\NilaiMahasiswa;
use Illuminate\Http\Request; // Ditambahkan untuk potensi penggunaan di masa depan
use Carbon\Carbon; // Ditambahkan untuk potensi penggunaan di masa depan

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard untuk mahasiswa.
     */
    public function mahasiswaDashboard()
    {
        // Logika untuk dashboard mahasiswa bisa ditambahkan di sini
        return view('mahasiswa.dashboard');
    }

    /**
     * Menampilkan dashboard untuk dosen.
     */
    public function dosenDashboard()
    {
        // Logika untuk dashboard dosen bisa ditambahkan di sini
        return view('dosen.dashboard');
    }

    /**
     * Menampilkan dashboard untuk admin dengan data ringkasan.
     */
    public function adminDashboard()
    {
        // Data untuk kartu statistik
        $totalUsers = User::count();
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $totalMataKuliah = MataKuliah::count();

        // Data untuk tabel ringkasan
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

    /**
     * Placeholder methods for other admin routes.
     * Implement the logic for these as needed.
     */
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
