<?php

namespace App\Http\Controllers;

use App\Models\NilaiMahasiswa;
use App\Models\PengampuMataKuliah;
use App\Models\PresensiMahasiswa;
use App\Models\Mahasiswa; // Make sure Mahasiswa model is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Keep for changePassword
use Carbon\Carbon;
use App\Models\MataKuliah; 
use App\Models\Dosen;       
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule; // Required for unique email validation

class MahasiswaController extends Controller
{
    public function dashboard()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect('/')->with('error', 'Data mahasiswa tidak ditemukan untuk akun ini.');
        }

        // Ensure $mahasiswa->kelas is not null before using it in a query
        $kelasMahasiswa = $mahasiswa->kelas;
        $upcomingClasses = collect(); // Default to empty collection

        if ($kelasMahasiswa) {
            $upcomingClasses = PengampuMataKuliah::where('kelas', $kelasMahasiswa)
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->with(['mataKuliah', 'dosen'])
                ->get();
        } else {
            Log::warning('Mahasiswa ID ' . $mahasiswa->id . ' does not have a kelas assigned.');
        }


        $recentGrades = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->with(['mataKuliah', 'dosen'])
            ->get();

        $nilaiMahasiswas = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->with('mataKuliah')
            ->orderBy('kelas', 'asc') 
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
        
        $jadwalKuliahs = collect();
        if ($mahasiswa->kelas) {
            $jadwalKuliahs = PengampuMataKuliah::where('kelas', $mahasiswa->kelas)
                ->with(['mataKuliah', 'dosen'])
                ->orderBy('jam_mulai')
                ->get();
        }


        $dayOrder = [
            'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4,
            'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7,
        ];

        $sortedJadwalKuliahs = $jadwalKuliahs->sort(function ($a, $b) use ($dayOrder) {
            $orderA = $dayOrder[$a->hari] ?? 99;
            $orderB = $dayOrder[$b->hari] ?? 99;
            if ($orderA === $orderB) {
                return strtotime($a->jam_mulai) <=> strtotime($b->jam_mulai);
            }
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

        // Sort by mata kuliah name within each group later if needed, or ensure mataKuliah relation is loaded
        $nilaiMahasiswas = $nilaiMahasiswas->sortBy(function($nilai) {
            return $nilai->mataKuliah->nama_mk ?? ''; // Handle if mataKuliah is null
        });


        $nilaiPerKelas = $nilaiMahasiswas->groupBy('kelas'); 

        return view('mahasiswa.khs', compact('mahasiswa', 'nilaiPerKelas'));
    }

      public function lihatKRS()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        
        $krsDetails = collect();
        if ($mahasiswa->kelas) {
            $krsDetails = PengampuMataKuliah::where('kelas', $mahasiswa->kelas)
                ->with(['mataKuliah', 'dosen'])
                ->get();
        }

        $totalSKS = $krsDetails->sum(function($item) {
            return $item->mataKuliah->sks ?? 0;
        });

        $krsDetails = $krsDetails->sortBy(function($item) {
            return $item->mataKuliah->nama_mk ?? '';
        });

        $displaySemesterTitle = 'Kelas ' . ($mahasiswa->kelas ?? 'Belum Ada Kelas');
        return view('mahasiswa.krs', compact('mahasiswa', 'displaySemesterTitle', 'krsDetails', 'totalSKS'));
    }

    public function lihatDetailPribadi()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data pribadi tidak ditemukan.');
        }
        // Eager load user relation if email is on user table
        $mahasiswa->load('user'); 

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
            Log::warning('Attempt to access presensi form without mahasiswa data for user ID: ' . Auth::id());
            return redirect('/')->with('error', 'Data mahasiswa tidak ditemukan untuk akun ini. Harap hubungi administrasi.');
        }

        $hariIniCarbon = Carbon::now()->locale('id');
        $hariIni = $hariIniCarbon->isoFormat('dddd');
        $tanggalHariIni = $hariIniCarbon->toDateString();

        $jadwalHariIni = PengampuMataKuliah::where('kelas', $mahasiswa->kelas)
            ->where('hari', $hariIni)
            ->with(['mataKuliah', 'dosen']) 
            ->orderBy('jam_mulai')
            ->get();

        $presensiSudahDilakukan = collect(); 
        if ($jadwalHariIni->isNotEmpty()) {
            $presensiSudahDilakukan = PresensiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                ->whereDate('tanggal', $tanggalHariIni)
                ->whereIn('pengampu_mata_kuliah_id', $jadwalHariIni->pluck('id'))
                ->get() 
                ->keyBy('pengampu_mata_kuliah_id'); 
        }
        
        return view('mahasiswa.presensi', compact('mahasiswa', 'jadwalHariIni', 'hariIni', 'presensiSudahDilakukan'));
    }

    public function submitPresensi(Request $request)
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'Sesi tidak valid atau data mahasiswa tidak ditemukan.'], 401);
        }

        try {
            $validatedData = $request->validate([
                'pengampu_mata_kuliah_id' => 'required|exists:pengampu_mata_kuliah,id', 
                'status_kehadiran' => 'required|string|in:Hadir,Tidak Hadir',
            ]);

            $pengampuMataKuliahId = $validatedData['pengampu_mata_kuliah_id'];
            $statusKehadiranInput = $validatedData['status_kehadiran']; 
            $tanggalPresensi = Carbon::today()->toDateString();

            $jadwal = PengampuMataKuliah::where('id', $pengampuMataKuliahId)
                                        ->where('kelas', $mahasiswa->kelas)
                                        ->where('hari', Carbon::now()->locale('id')->isoFormat('dddd'))
                                        ->first();
            if (!$jadwal) {
                return response()->json(['success' => false, 'message' => 'Jadwal tidak valid atau sudah lewat untuk Anda.'], 403);
            }
            
            $statusKehadiranDB = $statusKehadiranInput; 


            PresensiMahasiswa::updateOrCreate(
                [
                    'mahasiswa_id' => $mahasiswa->id,
                    'pengampu_mata_kuliah_id' => $pengampuMataKuliahId,
                    'tanggal' => $tanggalPresensi,
                ],
                [
                    'waktu_presensi' => Carbon::now()->toTimeString(),
                    'status_kehadiran' => $statusKehadiranDB, 
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Presensi berhasil dicatat sebagai: ' . $statusKehadiranInput, 
                'status_kehadiran_display' => $statusKehadiranInput, 
                'pengampu_mata_kuliah_id' => $pengampuMataKuliahId
            ]);

        } catch (ValidationException $e) {
            Log::error('Validation Error for Mahasiswa Presensi (Toggle): ' . $e->getMessage(), ['errors' => $e->errors()]);
            $errorMessages = [];
            foreach($e->errors() as $field => $messages) {
                $errorMessages[] = $messages[0];
            }
            return response()->json(['success' => false, 'message' => implode(' ', $errorMessages), 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error submitting presensi for Mahasiswa (Toggle): ' . $e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan internal server. Silakan coba lagi nanti.'], 500);
        }
    }

    // --- Metode Baru untuk Edit Detail Pribadi ---
    /**
     * Show the form for editing the student's personal details.
     */
    public function editDetailPribadi()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        $mahasiswa->load('user'); // Eager load user untuk akses email dan nama jika di tabel user
        return view('mahasiswa.edit_detail_pribadi', compact('mahasiswa'));
    }

    /**
     * Update the student's personal details in storage.
     */
    public function updateDetailPribadi(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Pastikan email unik, kecuali untuk user ini sendiri
            ],
            'telepon' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date|before_or_equal:today',
            'alamat' => 'nullable|string|max:500',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.'
        ]);

        try {
            // Update data di tabel users (jika nama dan email ada di sana)
            $user->name = $validatedData['nama']; // Asumsi 'name' di tabel users adalah nama lengkap
            $user->email = $validatedData['email'];
            $user->save();

            // Update data di tabel mahasiswas
            $mahasiswa->telepon = $validatedData['telepon'];
            $mahasiswa->tanggal_lahir = $validatedData['tanggal_lahir'];
            $mahasiswa->alamat = $validatedData['alamat'];
            // Jika 'nama' juga ada di tabel mahasiswas dan berbeda dari users.name
            // $mahasiswa->nama = $validatedData['nama']; 
            $mahasiswa->save();

            return redirect()->route('mahasiswa.profil.detail')->with('success', 'Detail pribadi berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error updating mahasiswa profile: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui profil. Silakan coba lagi.');
        }
    }
    // --- Akhir Metode Baru ---


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
        $user->password = Hash::make($request->new_password); // Menggunakan Hash::make()
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah!');
    }
}
