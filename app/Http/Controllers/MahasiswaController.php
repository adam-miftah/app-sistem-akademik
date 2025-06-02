<?php

namespace App\Http\Controllers;

use App\Models\NilaiMahasiswa;
use App\Models\PengampuMataKuliah;
use App\Models\PresensiMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MahasiswaController extends Controller
{
    public function dashboard()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect('/')->with('error', 'Data mahasiswa tidak ditemukan untuk akun ini.');
        }

        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        $upcomingClasses = PengampuMataKuliah::where('kelas', $mahasiswa->kelas)
                                        ->orderBy('hari')
                                        ->orderBy('jam_mulai')
                                        ->with(['mataKuliah', 'dosen'])
                                        ->get();

        $recentGrades = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                                        ->orderBy('created_at', 'desc')
                                        ->limit(5)
                                        ->with(['mataKuliah', 'dosen'])
                                        ->get();

        $nilaiMahasiswas = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                                            ->with('mataKuliah')
                                            ->orderBy('kelas', 'asc') // Diubah dari 'semester' ke 'kelas'
                                            ->get();

        $ipsData = [];
        $ipkData = [];
        $totalSKSKumulatif = 0;
        $totalSKSxMutuKumulatif = 0;

        $nilaiPerKelas = $nilaiMahasiswas->groupBy('kelas');

        foreach ($nilaiPerKelas as $kelas => $nilaiList) {
            $totalSKSPerKelas = 0;
            $totalSKSxMutuPerKelas = 0;

            foreach ($nilaiList as $nilai) {
                if ($nilai->mutu !== null && $nilai->mataKuliah && $nilai->mataKuliah->sks !== null) {
                    $totalSKSPerKelas += $nilai->mataKuliah->sks;
                    $totalSKSxMutuPerKelas += ($nilai->mataKuliah->sks * $nilai->mutu);
                }
            }

            $ips = ($totalSKSPerKelas > 0) ? round($totalSKSxMutuPerKelas / $totalSKSPerKelas, 2) : 0.00;
            $ipsData[$kelas] = $ips;

            $totalSKSKumulatif += $totalSKSPerKelas;
            $totalSKSxMutuKumulatif += $totalSKSxMutuPerKelas;
            $ipk = ($totalSKSKumulatif > 0) ? round($totalSKSxMutuKumulatif / $totalSKSKumulatif, 2) : 0.00;
            $ipkData[$kelas] = $ipk;
        }

        $chartLabels = array_keys($ipsData);
        $chartIPSValues = array_values($ipsData);
        $chartIPKValues = array_values($ipkData);

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'upcomingClasses',
            'recentGrades',
            'chartLabels',
            'chartIPSValues',
            'chartIPKValues'
        ));
    }

    public function lihatJadwalKuliah()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect('/')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $jadwalKuliahs = PengampuMataKuliah::where('kelas', $mahasiswa->kelas)
                                            ->with(['mataKuliah', 'dosen'])
                                            ->orderBy('jam_mulai')
                                            ->get();

        // Definisikan urutan hari yang Anda inginkan
        $dayOrder = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 7,
        ];

        $sortedJadwalKuliahs = $jadwalKuliahs->sort(function ($a, $b) use ($dayOrder) {
            $orderA = $dayOrder[$a->hari] ?? 99;
            $orderB = $dayOrder[$b->hari] ?? 99;
            return $orderA <=> $orderB;
        });

        return view('mahasiswa.jadwal_kuliah', compact('sortedJadwalKuliahs', 'mahasiswa'));
    }
    public function lihatKHS()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect('/')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $nilaiMahasiswas = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                                            ->with(['mataKuliah', 'dosen'])
                                            ->orderBy('kelas', 'asc')
                                            ->get();

        $nilaiMahasiswas = $nilaiMahasiswas->sortBy(function($nilai) {
            return $nilai->mataKuliah->nama_mk;
        });

        $nilaiPerKelas = $nilaiMahasiswas->groupBy('kelas'); // Diubah dari 'nilaiPerSemester' ke 'nilaiPerKelas'

        return view('mahasiswa.khs', compact('mahasiswa', 'nilaiPerKelas')); // Diubah dari 'nilaiPerSemester'
    }

      public function lihatKRS()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $krsDetails = PengampuMataKuliah::where('kelas', $mahasiswa->kelas)
                                         ->with(['mataKuliah', 'dosen'])
                                         ->get();

        $totalSKS = $krsDetails->sum(function($item) {
            return $item->mataKuliah->sks ?? 0;
        });

        $krsDetails = $krsDetails->sortBy(function($item) {
            return $item->mataKuliah->nama_mk;
        });

        $displaySemesterTitle = 'Kelas ' . $mahasiswa->kelas;
        return view('mahasiswa.krs', compact('mahasiswa', 'displaySemesterTitle', 'krsDetails', 'totalSKS'));
    }

    public function lihatDetailPribadi()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data pribadi tidak ditemukan.');
        }

        return view('mahasiswa.detail_pribadi', compact('mahasiswa'));
    }
    public function lihatRangkumanNilai()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $nilaiMahasiswas = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                                            ->with('mataKuliah')
                                            ->orderBy('kelas', 'asc')
                                            ->get();

        $nilaiPerKelas = $nilaiMahasiswas->groupBy('kelas');

        $compiledGrades = collect();
        $totalSKSKumulatif = 0;
        $totalSKSxMutuKumulatif = 0;

        foreach ($nilaiPerKelas as $kelas => $nilaiList) {
            $semesterData = [
                'kelas' => $kelas,
                'grades' => collect(),
                'total_sks_kelas' => 0,
                'total_sks_x_mutu_kelas' => 0,
                'ipk_kelas' => 0.00,
            ];

            foreach ($nilaiList as $nilai) {
                if ($nilai->mataKuliah) {
                    $semesterData['grades']->push([
                        'kode_mk' => $nilai->mataKuliah->kode_mk,
                        'nama_mk' => $nilai->mataKuliah->nama_mk,
                        'sks' => $nilai->mataKuliah->sks,
                        'nilai_angka' => $nilai->nilai_angka ?? '-',
                        'nilai_huruf' => $nilai->nilai_huruf ?? '-',
                        'mutu' => $nilai->mutu ?? '-',
                    ]);

                    if ($nilai->mutu !== null && $nilai->mataKuliah->sks !== null) {
                        $semesterData['total_sks_kelas'] += $nilai->mataKuliah->sks;
                        $semesterData['total_sks_x_mutu_kelas'] += ($nilai->mataKuliah->sks * $nilai->mutu);
                    }
                }
            }

            $semesterData['ipk_kelas'] = ($semesterData['total_sks_kelas'] > 0) ?
                                        round($semesterData['total_sks_x_mutu_kelas'] / $semesterData['total_sks_kelas'], 2) : 0.00;

            $compiledGrades->push($semesterData);

            $totalSKSKumulatif += $semesterData['total_sks_kelas'];
            $totalSKSxMutuKumulatif += $semesterData['total_sks_x_mutu_kelas'];
        }

        $ipkKumulatif = ($totalSKSKumulatif > 0) ? round($totalSKSxMutuKumulatif / $totalSKSKumulatif, 2) : 0.00;
        return view('mahasiswa.rangkuman_nilai', compact('mahasiswa', 'compiledGrades', 'ipkKumulatif', 'totalSKSKumulatif'));
    }

    public function showPresensiForm()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect('/')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $dayOfWeek = Carbon::now()->dayOfWeek;
        $namaHariIndonesia = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];
        $hariIni = $namaHariIndonesia[$dayOfWeek];
        $jadwalHariIni = PengampuMataKuliah::where('kelas', $mahasiswa->kelas)
                                            ->where('hari', $hariIni)
                                            ->with(['mataKuliah', 'dosen'])
                                            ->orderBy('jam_mulai')
                                            ->get();

        return view('mahasiswa.presensi', compact('mahasiswa', 'jadwalHariIni', 'hariIni'));
    }

    public function submitPresensi(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Data mahasiswa tidak ditemukan.'], 404);
        }

        $request->validate([
            'pengampu_mata_kuliah_id' => 'required|exists:pengampu_mata_kuliahs,id',
            'status_kehadiran' => 'required|string|in:Hadir,Sakit,Izin,Alpha',
        ], [
            'pengampu_mata_kuliah_id.required' => 'Mata kuliah wajib dipilih.',
            'pengampu_mata_kuliah_id.exists' => 'Mata kuliah tidak valid.',
            'status_kehadiran.required' => 'Status kehadiran wajib dipilih.',
            'status_kehadiran.in' => 'Status kehadiran tidak valid.',
        ]);

        $pengampuMataKuliahId = $request->pengampu_mata_kuliah_id;
        $tanggalPresensi = Carbon::today()->toDateString();

        $existingPresensi = PresensiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                                            ->where('pengampu_mata_kuliah_id', $pengampuMataKuliahId)
                                            ->whereDate('tanggal', $tanggalPresensi)
                                            ->first();

        if ($existingPresensi) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan presensi untuk mata kuliah ini hari ini.'], 409);
        }

        try {
            PresensiMahasiswa::create([
                'mahasiswa_id' => $mahasiswa->id,
                'pengampu_mata_kuliah_id' => $pengampuMataKuliahId,
                'tanggal' => $tanggalPresensi,
                'waktu_presensi' => Carbon::now()->toTimeString(),
                'status_kehadiran' => $request->status_kehadiran,
            ]);

            return response()->json(['success' => true, 'message' => 'Presensi berhasil dicatat!', 'status_kehadiran' => $request->status_kehadiran]);
        } catch (\Exception $e) {
            \Log::error('Error submitting presensi: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mencatat presensi. Silakan coba lagi.'], 500);
        }
    }
   public function showChangePasswordForm()
    {
        return view('mahasiswa.settings.change_password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah!');
    }
}