<?php

namespace App\Http\Controllers;

use App\Models\NilaiMahasiswa;
use App\Models\PengampuMataKuliah;
use App\Models\PresensiMahasiswa;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Keep for changePassword
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 
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

        // 1. Ambil Data KRS & Hitung Total SKS Semester Ini
        $totalSksKrs = 0;
        if ($mahasiswa->kelas) {
            $krsDetails = PengampuMataKuliah::where('kelas', $mahasiswa->kelas)
                ->with('mataKuliah')
                ->get();
            $totalSksKrs = $krsDetails->sum(fn($item) => optional($item->mataKuliah)->sks ?? 0);
        }

        // 2. Ambil Jadwal Kuliah Hari Ini
        $upcomingClasses = collect();
        if ($mahasiswa->kelas) {
            $upcomingClasses = PengampuMataKuliah::where('kelas', $mahasiswa->kelas)
                ->where('hari', Carbon::now()->locale('id')->isoFormat('dddd'))
                ->orderBy('jam_mulai')
                ->with(['mataKuliah', 'dosen'])
                ->get();
        }

        // 3. Ambil Nilai Terbaru untuk Ditampilkan
        $recentGrades = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->latest()
            ->limit(5)
            ->with(['mataKuliah'])
            ->get();

        // 4. Ambil Semua Nilai untuk Kalkulasi Grafik IPS & IPK
        $nilaiMahasiswas = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->with('mataKuliah')
            ->orderBy('kelas', 'asc') 
            ->get();

        $ipsData = [];
        $ipkData = [];
        $totalSKSKumulatif = 0;
        $totalMutuKaliSKSKumulatif = 0;
        $nilaiPerKelas = $nilaiMahasiswas->groupBy('kelas');
        
        foreach ($nilaiPerKelas as $kelas => $nilaiList) {
            $totalSKSPerKelas = $nilaiList->sum(fn($n) => optional($n->mataKuliah)->sks ?? 0);
            $totalMutuKaliSKS = $nilaiList->sum(fn($n) => (optional($n->mataKuliah)->sks ?? 0) * ($n->mutu ?? 0));
            $ips = ($totalSKSPerKelas > 0) ? round($totalMutuKaliSKS / $totalSKSPerKelas, 2) : 0.00;
            $ipsData[$kelas] = $ips;
            
            $totalSKSKumulatif += $totalSKSPerKelas;
            $totalMutuKaliSKSKumulatif += $totalMutuKaliSKS;
            $ipk = ($totalSKSKumulatif > 0) ? round($totalMutuKaliSKSKumulatif / $totalSKSKumulatif, 2) : 0.00;
            $ipkData[$kelas] = $ipk;
        }

        $chartLabels = array_keys($ipsData);
        $chartIPSValues = array_values($ipsData);
        $chartIPKValues = array_values($ipkData);

        // 5. Ambil Data Pengumuman
        $announcements = Announcement::where(function ($query) {
            $query->where('target_role', 'Mahasiswa')
                  ->orWhere('target_role', 'Semua');
        })
        ->latest()
        ->limit(5)
        ->get();
        
        // 6. Kirim SEMUA data ke view dalam SATU kali return
        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'totalSksKrs',
            'upcomingClasses',
            'recentGrades',
            'chartLabels',
            'chartIPSValues',
            'chartIPKValues',
            'announcements' // Variabel pengumuman sekarang sudah ada
        ));
    }


    public function lihatJadwalKuliah()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect('/')->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        
        $jadwalKuliahs = collect(); // Default ke koleksi kosong
        if ($mahasiswa->kelas) {
            // --- INI BAGIAN YANG DIUBAH ---
            // Mengambil data dan langsung mengurutkannya di database
            $jadwalKuliahs = PengampuMataKuliah::where('kelas', $mahasiswa->kelas)
                ->with(['mataKuliah', 'dosen'])
                ->orderBy(DB::raw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')"))
                ->orderBy('jam_mulai', 'asc')
                ->get();
        }

        // Variabel yang dikirim ke view sekarang kita namakan $jadwalKuliahs agar konsisten
        return view('mahasiswa.jadwal_kuliah', compact('jadwalKuliahs', 'mahasiswa'));
    }
    public function lihatKHS()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect('/')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Ambil semua data nilai sebagai satu daftar (flat list)
        // dan urutkan langsung dari database agar efisien.
        $nilaiMahasiswas = NilaiMahasiswa::where('nilai_mahasiswas.mahasiswa_id', $mahasiswa->id)
            ->with('mataKuliah')
            ->leftJoin('mata_kuliahs', 'nilai_mahasiswas.mata_kuliah_id', '=', 'mata_kuliahs.id')
            ->orderBy('nilai_mahasiswas.kelas', 'asc') // Urutkan berdasarkan kelas/semester
            ->orderBy('mata_kuliahs.nama_mk', 'asc')   // Lalu urutkan berdasarkan nama MK
            ->select('nilai_mahasiswas.*')
            ->get();
        
        // Kirim data mahasiswa dan daftar nilainya ke view
        return view('mahasiswa.khs', compact('mahasiswa', 'nilaiMahasiswas'));
    }

     public function lihatKRS()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        
        $krsDetails = collect();
        if ($mahasiswa->kelas) {
            // === PERBAIKAN QUERY DI SINI ===
            // Ganti semua 'pengampu_mata_kuliahs' menjadi 'pengampu_mata_kuliah'
            $krsDetails = PengampuMataKuliah::where('pengampu_mata_kuliah.kelas', $mahasiswa->kelas)
                ->with(['mataKuliah', 'dosen'])
                ->join('mata_kuliahs', 'pengampu_mata_kuliah.mata_kuliah_id', '=', 'mata_kuliahs.id')
                ->orderBy('mata_kuliahs.nama_mk', 'asc')
                ->select('pengampu_mata_kuliah.*')
                ->get();
        }

        $totalSKS = $krsDetails->sum(function($item) {
            return optional($item->mataKuliah)->sks ?? 0;
        });

        return view('mahasiswa.krs', compact('mahasiswa', 'krsDetails', 'totalSKS'));
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
            ->get()->sortBy(function($nilai) {
                return $nilai->kelas . optional($nilai->mataKuliah)->nama_mk;
            });
            
        // Lakukan kalkulasi total SKS dan total Mutu di sini
        $totalSKSKumulatif = 0;
        $totalMutuKumulatif = 0; // Variabel baru untuk Total Mutu

        foreach ($nilaiMahasiswas as $nilai) {
            $totalSKSKumulatif += optional($nilai->mataKuliah)->sks ?? 0;
            // Panggil accessor baru sks_x_mutu yang sudah kita buat
            $totalMutuKumulatif += $nilai->sks_x_mutu; 
        }
        
        $ipkKumulatif = ($totalSKSKumulatif > 0) ? round($totalMutuKumulatif / $totalSKSKumulatif, 2) : 0.00;
        
        return view('mahasiswa.rangkuman_nilai', compact('mahasiswa', 'nilaiMahasiswas', 'ipkKumulatif', 'totalSKSKumulatif', 'totalMutuKumulatif'));
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

    public function editDetailPribadi()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        $mahasiswa->load('user'); // Eager load user untuk akses email dan nama jika di tabel user
        return view('mahasiswa.edit_detail_pribadi', compact('mahasiswa'));
    }


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
