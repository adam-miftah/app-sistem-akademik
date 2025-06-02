<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa; 
use App\Models\NilaiMahasiswa;
use App\Models\PengampuMataKuliah; 
use App\Models\PresensiMahasiswa; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 

class DashboardController extends Controller
{
    public function dashboard()
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) {
            return redirect('/')->with('error', 'Data dosen tidak ditemukan untuk akun ini.');
        }

        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        $totalMataKuliahDiajar = PengampuMataKuliah::where('dosen_id', $dosen->id)->count();
        $totalJadwalMengajar = PengampuMataKuliah::where('dosen_id', $dosen->id)->count();
        $totalNilaiDiinput = NilaiMahasiswa::where('dosen_id', $dosen->id)->count();

        $upcomingSchedules = PengampuMataKuliah::where('dosen_id', $dosen->id)
                                             ->orderBy('hari')
                                             ->orderBy('jam_mulai')
                                             ->limit(5)
                                             ->with(['mataKuliah', 'dosen'])
                                             ->get();

        $recentGradesInputted = NilaiMahasiswa::where('dosen_id', $dosen->id)
                                              ->orderBy('created_at', 'desc')
                                              ->limit(5)
                                              ->with(['mahasiswa', 'mataKuliah'])
                                              ->get();

        return view('dosen.dashboard', compact('dosen', 'totalMataKuliahDiajar', 'totalJadwalMengajar', 'totalNilaiDiinput', 'upcomingSchedules', 'recentGradesInputted', 'kelasOptions'));
    }

    public function lihatJadwalMengajar()
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) {
            return redirect('/')->with('error', 'Data dosen tidak ditemukan.');
        }

        $jadwalMengajar = PengampuMataKuliah::where('dosen_id', $dosen->id)
                                         ->with(['mataKuliah', 'dosen'])
                                         ->orderBy('hari')
                                         ->orderBy('jam_mulai')
                                         ->get();

        return view('dosen.jadwal_mengajar', compact('dosen', 'jadwalMengajar'));
    }

    public function kelolaNilaiMahasiswa()
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) {
            return redirect('/')->with('error', 'Data dosen tidak ditemukan.');
        }

        $nilaiMahasiswas = NilaiMahasiswa::where('dosen_id', $dosen->id)
                                         ->with(['mahasiswa', 'mataKuliah', 'dosen'])
                                         ->orderBy('kelas', 'asc')
                                         ->get();

        $nilaiPerKelas = $nilaiMahasiswas->groupBy('kelas');
        return view('dosen.kelola_nilai_mahasiswa', compact('dosen', 'nilaiPerKelas'));
    }

    public function createNilai()
    {
        $dosen = Auth::user()->dosen;
        if (!$dosen) {
            return redirect('/')->with('error', 'Data dosen tidak ditemukan.');
        }

        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $pengampuMataKuliahs = PengampuMataKuliah::where('dosen_id', $dosen->id)
                                                 ->with('mataKuliah')
                                                 ->get();

        return view('dosen.input_nilai', compact('dosen', 'mahasiswas', 'pengampuMataKuliahs'));
    }

    public function storeNilai(Request $request)
    {
        $dosen = Auth::user()->dosen;

        if (!$dosen) {
            return redirect('/')->with('error', 'Data dosen tidak ditemukan.');
        }

        $pengampuMataKuliah = PengampuMataKuliah::find($request->pengampu_mata_kuliah_id);

        if (!$pengampuMataKuliah || $pengampuMataKuliah->dosen_id !== $dosen->id) {
            return redirect()->back()->withInput()->with('error', 'Mata kuliah yang dipilih tidak valid atau Anda tidak berhak menginput nilai untuk mata kuliah tersebut.');
        }

        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'pengampu_mata_kuliah_id' => 'required|exists:pengampu_mata_kuliahs,id',
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
        ], [
            'mahasiswa_id.required' => 'Mahasiswa wajib dipilih.',
            'pengampu_mata_kuliah_id.required' => 'Mata kuliah wajib dipilih.',
            'pengampu_mata_kuliah_id.exists' => 'Mata kuliah yang dipilih tidak valid.',
            'nilai_tugas.numeric' => 'Nilai tugas harus berupa angka.',
            'nilai_uts.numeric' => 'Nilai UTS harus berupa angka.',
            'nilai_uas.numeric' => 'Nilai UAS harus berupa angka.',
        ]);

        $jumlahKehadiran = PresensiMahasiswa::where('mahasiswa_id', $request->mahasiswa_id)
                                            ->where('pengampu_mata_kuliah_id', $request->pengampu_mata_kuliah_id)
                                            ->where('status_kehadiran', 'Hadir')
                                            ->count();

        $existingNilai = NilaiMahasiswa::where('mahasiswa_id', $request->mahasiswa_id)
                                       ->where('mata_kuliah_id', $pengampuMataKuliah->mata_kuliah_id)
                                       ->where('kelas', $pengampuMataKuliah->kelas)
                                       ->first();

        if ($existingNilai) {
            return redirect()->back()->withInput()->with('error', 'Nilai untuk mahasiswa ini pada mata kuliah dan kelas yang sama sudah ada. Gunakan fitur edit jika ingin mengubah.');
        }

        NilaiMahasiswa::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'mata_kuliah_id' => $pengampuMataKuliah->mata_kuliah_id,
            'dosen_id' => $dosen->id,
            'kelas' => $pengampuMataKuliah->kelas,
            'kehadiran' => $jumlahKehadiran,
            'nilai_tugas' => $request->nilai_tugas,
            'nilai_uts' => $request->nilai_uts,
            'nilai_uas' => $request->nilai_uas,
            'nilai_huruf' => null, 
        ]);

        return redirect()->route('dosen.kelolaNilaiMahasiswa')->with('success', 'Nilai mahasiswa berhasil ditambahkan!');
    }
    public function lihatDaftarMahasiswa(Request $request)
    {
        $dosen = Auth::user()->dosen;

        if (!$dosen) {
            return redirect('/')->with('error', 'Data dosen tidak ditemukan.');
        }

        $kelasDiajarOlehDosen = PengampuMataKuliah::where('dosen_id', $dosen->id)
                                                 ->pluck('kelas')
                                                 ->unique()
                                                 ->toArray();

        $mataKuliahIdsDiajar = PengampuMataKuliah::where('dosen_id', $dosen->id)
                                                 ->pluck('mata_kuliah_id')
                                                 ->unique();
        $mataKuliahsDiajar = \App\Models\MataKuliah::whereIn('id', $mataKuliahIdsDiajar)
                                                   ->orderBy('nama_mk')
                                                   ->get();


        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        $selectedKelas = $request->input('kelas');
        $selectedMataKuliah = $request->input('mata_kuliah_id');

        $pengampuDetailForDisplay = null;
        if ($selectedMataKuliah && $selectedKelas) {
            $pengampuDetailForDisplay = PengampuMataKuliah::where('dosen_id', $dosen->id)
                                                          ->where('mata_kuliah_id', $selectedMataKuliah)
                                                          ->where('kelas', $selectedKelas)
                                                          ->first();
        }

        $mahasiswasQuery = Mahasiswa::query();

        if (!empty($kelasDiajarOlehDosen)) {
            $mahasiswasQuery->whereIn('kelas', $kelasDiajarOlehDosen);
        } else {
            $mahasiswasQuery->whereRaw('1 = 0');
        }

        if ($selectedKelas && $selectedKelas !== '') {
            $mahasiswasQuery->where('kelas', $selectedKelas);
        }

        if ($selectedMataKuliah && $selectedMataKuliah !== '') {
            $pengampuIdsForFilter = PengampuMataKuliah::where('dosen_id', $dosen->id)
                                                      ->where('mata_kuliah_id', $selectedMataKuliah)
                                                      ->when($selectedKelas, function ($query, $kelas) {
                                                          return $query->where('kelas', $kelas);
                                                      })
                                                      ->pluck('id');
            
            $mahasiswaKelasFromPengampu = PengampuMataKuliah::whereIn('id', $pengampuIdsForFilter)
                                                            ->pluck('kelas')
                                                            ->unique();
            
            $mahasiswasQuery->whereIn('kelas', $mahasiswaKelasFromPengampu);
        }


        $mahasiswas = $mahasiswasQuery->orderBy('nama')->get();

        $tanggalHariIni = Carbon::today()->toDateString();
        $hariIniCarbon = Carbon::now()->isoFormat('dddd');
        
        $pengampuIdsHariIni = PengampuMataKuliah::where('dosen_id', $dosen->id)
                                                 ->where('hari', $hariIniCarbon)
                                                 ->pluck('id');

        $presensiStatusHariIni = PresensiMahasiswa::whereIn('mahasiswa_id', $mahasiswas->pluck('id'))
                                                 ->whereIn('pengampu_mata_kuliah_id', $pengampuIdsHariIni)
                                                 ->whereDate('tanggal', $tanggalHariIni)
                                                 ->where('status_kehadiran', 'Hadir')
                                                 ->pluck('status_kehadiran', 'mahasiswa_id');

        return view('dosen.daftar_mahasiswa', compact('dosen', 'mahasiswas', 'kelasOptions', 'selectedKelas', 'mataKuliahsDiajar', 'selectedMataKuliah', 'presensiStatusHariIni', 'pengampuDetailForDisplay'));
    }

    public function togglePresensiHarian(Request $request)
    {
        try {
            $dosen = Auth::user()->dosen;

            if (!$dosen) {
                return response()->json(['success' => false, 'message' => 'Data dosen tidak ditemukan atau Anda tidak terautentikasi.'], 401);
            }

            $request->validate([
                'mahasiswa_id' => 'required|exists:mahasiswas,id',
                'is_present' => 'required|boolean',
            ]);

            $mahasiswaId = $request->mahasiswa_id;
            $isPresent = $request->is_present;
            $tanggalHariIni = Carbon::today()->toDateString();
            $hariIniCarbon = Carbon::now()->isoFormat('dddd');

            $mahasiswaObj = Mahasiswa::find($mahasiswaId);
            if (!$mahasiswaObj) {
                    return response()->json(['success' => false, 'message' => 'Mahasiswa tidak ditemukan.'], 404);
            }

            $pengampuJadwalHariIni = PengampuMataKuliah::where('dosen_id', $dosen->id)
                                                         ->where('kelas', $mahasiswaObj->kelas)
                                                         ->where('hari', $hariIniCarbon)
                                                         ->pluck('id');
            
            if ($pengampuJadwalHariIni->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Tidak ada jadwal mata kuliah yang ditemukan untuk mahasiswa ini hari ini yang Anda ampu.'], 404);
            }

            $statusKehadiran = $isPresent ? 'Hadir' : 'Alpha';

            PresensiMahasiswa::where('mahasiswa_id', $mahasiswaId)
                             ->whereIn('pengampu_mata_kuliah_id', $pengampuJadwalHariIni)
                             ->whereDate('tanggal', $tanggalHariIni)
                             ->delete();

            if ($isPresent) {
                foreach ($pengampuJadwalHariIni as $pengampuId) {
                    PresensiMahasiswa::create([
                        'mahasiswa_id' => $mahasiswaId,
                        'pengampu_mata_kuliah_id' => $pengampuId,
                        'tanggal' => $tanggalHariIni,
                        'waktu_presensi' => Carbon::now()->toTimeString(),
                        'status_kehadiran' => $statusKehadiran,
                    ]);
                }
            }

            return response()->json(['success' => true, 'message' => 'Presensi berhasil diperbarui!', 'status_kehadiran' => $statusKehadiran]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error in togglePresensiHarian: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return response()->json(['success' => false, 'message' => 'Data tidak valid.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error toggling presensi: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server. Silakan coba lagi.'], 500);
        }
    }
    public function showChangePasswordForm()
    {
        return view('dosen.settings.change_password'); 
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
