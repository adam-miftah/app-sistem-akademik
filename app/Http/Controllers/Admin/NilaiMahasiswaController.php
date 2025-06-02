<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NilaiMahasiswa;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Dosen;
use App\Models\PresensiMahasiswa;
use App\Models\PengampuMataKuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class NilaiMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $filterMahasiswaNama = $request->input('mahasiswa_nama');
        $filterMahasiswaNIM = $request->input('mahasiswa_nim');

        $query = NilaiMahasiswa::query();

        $query->with(['mahasiswa', 'mataKuliah', 'dosen']);

        if (!empty($filterMahasiswaNama)) {
            $query->whereHas('mahasiswa', function ($q) use ($filterMahasiswaNama) {
                $q->where('nama', 'like', '%' . $filterMahasiswaNama . '%');
            });
        }

        if (!empty($filterMahasiswaNIM)) {
            $query->whereHas('mahasiswa', function ($q) use ($filterMahasiswaNIM) {
                $q->where('nim', 'like', '%' . $filterMahasiswaNIM . '%');
            });
        }

        $nilaiMahasiswas = $query->orderBy('mahasiswa_id') 
                                 ->orderBy('mata_kuliah_id') 
                                 ->get();

        return view('admin.nilai_mahasiswa.index', compact('nilaiMahasiswas'));
    }

    public function create()
    {
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        $dosens = Dosen::orderBy('nama')->get();
        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        return view('admin.nilai_mahasiswa.create', compact('mahasiswas', 'mataKuliahs', 'dosens', 'kelasOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'nullable|exists:dosens,id',
            'kelas' => [
                'required',
                'string',
                'max:10',
                Rule::unique('nilai_mahasiswas')->where(function ($query) use ($request) {
                    return $query->where('mahasiswa_id', $request->mahasiswa_id)
                                 ->where('mata_kuliah_id', $request->mata_kuliah_id);
                })
            ],
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
        ], [
            'kelas.unique' => 'Nilai untuk mahasiswa ini pada mata kuliah dan kelas yang sama sudah ada.',
            'nilai_tugas.numeric' => 'Nilai tugas harus berupa angka.',
            'nilai_uts.numeric' => 'Nilai UTS harus berupa angka.',
            'nilai_uas.numeric' => 'Nilai UAS harus berupa angka.',
            'nilai_tugas.min' => 'Nilai tugas tidak boleh kurang dari 0.',
            'nilai_tugas.max' => 'Nilai tugas tidak boleh lebih dari 100.',
            'nilai_uts.min' => 'Nilai UTS tidak boleh kurang dari 0.',
            'nilai_uts.max' => 'Nilai UTS tidak boleh lebih dari 100.',
            'nilai_uas.min' => 'Nilai UAS tidak boleh kurang dari 0.',
            'nilai_uas.max' => 'Nilai UAS tidak boleh lebih dari 100.',
        ]);

        $pengampuMataKuliah = PengampuMataKuliah::where('mata_kuliah_id', $request->mata_kuliah_id)
                                                ->where('kelas', $request->kelas)
                                                ->first();

        $jumlahKehadiran = 0;
        if ($pengampuMataKuliah) {
            $jumlahKehadiran = PresensiMahasiswa::where('mahasiswa_id', $request->mahasiswa_id)
                                                ->where('pengampu_mata_kuliah_id', $pengampuMataKuliah->id)
                                                ->where('status_kehadiran', 'Hadir')
                                                ->count();
        } else {
            \Log::warning('Pengampu Mata Kuliah tidak ditemukan untuk Mahasiswa ID: ' . $request->mahasiswa_id . ', Mata Kuliah ID: ' . $request->mata_kuliah_id . ', Kelas: ' . $request->kelas);
        }

        NilaiMahasiswa::create([
            'mahasiswa_id' => $request->mahasiswa_id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'dosen_id' => $request->dosen_id,
            'kelas' => $request->kelas,
            'kehadiran' => $jumlahKehadiran,
            'nilai_tugas' => $request->nilai_tugas,
            'nilai_uts' => $request->nilai_uts,
            'nilai_uas' => $request->nilai_uas,
            'nilai_huruf' => null,
        ]);

        return redirect()->route('admin.nilaiMahasiswas.index')->with('success', 'Nilai mahasiswa berhasil ditambahkan!');
    }

    public function show(NilaiMahasiswa $nilaiMahasiswa)
    {
        return redirect()->route('admin.nilaiMahasiswas.edit', $nilaiMahasiswa);
    }

    public function edit(NilaiMahasiswa $nilaiMahasiswa)
    {
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        $dosens = Dosen::orderBy('nama')->get();
        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        return view('admin.nilai_mahasiswa.edit', compact('nilaiMahasiswa', 'mahasiswas', 'mataKuliahs', 'dosens', 'kelasOptions'));
    }

    public function update(Request $request, NilaiMahasiswa $nilaiMahasiswa)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'nullable|exists:dosens,id',
            'kelas' => [
                'required',
                'string',
                'max:10',
                Rule::unique('nilai_mahasiswas')->where(function ($query) use ($request) {
                    return $query->where('mahasiswa_id', $request->mahasiswa_id)
                                 ->where('mata_kuliah_id', $request->mata_kuliah_id);
                })->ignore($nilaiMahasiswa->id)
            ],
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
        ], [
            'kelas.unique' => 'Nilai untuk mahasiswa ini pada mata kuliah dan kelas yang sama sudah ada.',
            'nilai_tugas.numeric' => 'Nilai tugas harus berupa angka.',
            'nilai_uts.numeric' => 'Nilai UTS harus berupa angka.',
            'nilai_uas.numeric' => 'Nilai UAS harus berupa angka.',
            'nilai_tugas.min' => 'Nilai tugas tidak boleh kurang dari 0.',
            'nilai_tugas.max' => 'Nilai tugas tidak boleh lebih dari 100.',
            'nilai_uts.min' => 'Nilai UTS tidak boleh kurang dari 0.',
            'nilai_uts.max' => 'Nilai UTS tidak boleh lebih dari 100.',
            'nilai_uas.min' => 'Nilai UAS tidak boleh kurang dari 0.',
            'nilai_uas.max' => 'Nilai UAS tidak boleh lebih dari 100.',
        ]);

        $pengampuMataKuliah = PengampuMataKuliah::where('mata_kuliah_id', $request->mata_kuliah_id)
                                                ->where('kelas', $request->kelas)
                                                ->first();

        $jumlahKehadiran = 0;
        if ($pengampuMataKuliah) {
            $jumlahKehadiran = PresensiMahasiswa::where('mahasiswa_id', $request->mahasiswa_id)
                                                ->where('pengampu_mata_kuliah_id', $pengampuMataKuliah->id)
                                                ->where('status_kehadiran', 'Hadir')
                                                ->count();
        } else {
            \Log::warning('Pengampu Mata Kuliah tidak ditemukan untuk Mahasiswa ID: ' . $request->mahasiswa_id . ', Mata Kuliah ID: ' . $request->mata_kuliah_id . ', Kelas: ' . $request->kelas);
        }

        $nilaiMahasiswa->update([
            'mahasiswa_id' => $request->mahasiswa_id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'dosen_id' => $request->dosen_id,
            'kelas' => $request->kelas,
            'kehadiran' => $jumlahKehadiran,
            'nilai_tugas' => $request->nilai_tugas,
            'nilai_uts' => $request->nilai_uts,
            'nilai_uas' => $request->nilai_uas,
            'nilai_huruf' => null, 
        ]);

        return redirect()->route('admin.nilaiMahasiswas.index')->with('success', 'Nilai mahasiswa berhasil diperbarui!');
    }
    public function destroy(NilaiMahasiswa $nilaiMahasiswa)
    {
        $nilaiMahasiswa->delete();

        return redirect()->route('admin.nilaiMahasiswas.index')->with('success', 'Nilai mahasiswa berhasil dihapus!');
    }

    public function getMahasiswaKelas(Mahasiswa $mahasiswa)
    {
        try {
            Log::info('getMahasiswaKelas: Mahasiswa ID ' . $mahasiswa->id . ' ditemukan.');
            Log::info('getMahasiswaKelas: Kelas mahasiswa: ' . ($mahasiswa->kelas ?? 'NULL'));
            return response()->json(['kelas' => $mahasiswa->kelas]);
        } catch (\Exception $e) {
            Log::error('Error in getMahasiswaKelas for Mahasiswa ID ' . $mahasiswa->id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data kelas.'], 500);
        }
    }

    public function getDosenPengampu(Request $request)
    {
        $mataKuliahId = $request->query('mata_kuliah_id');
        $kelas = $request->query('kelas');

        Log::info("getDosenPengampu: Mencari pengampu untuk Mata Kuliah ID: {$mataKuliahId}, Kelas: {$kelas}");

        if (!$mataKuliahId || !$kelas) {
            Log::warning('getDosenPengampu: Mata Kuliah ID atau Kelas tidak lengkap.');
            return response()->json(['dosen_id' => null, 'message' => 'Mata Kuliah ID dan Kelas diperlukan.'], 400);
        }

        try {
            $pengampu = PengampuMataKuliah::where('mata_kuliah_id', $mataKuliahId)
                                          ->where('kelas', $kelas)
                                          ->with('dosen') 
                                          ->first();

            if ($pengampu && $pengampu->dosen) {
                Log::info('getDosenPengampu: Dosen ditemukan: ' . $pengampu->dosen->nama . ' (ID: ' . $pengampu->dosen_id . ')');
                return response()->json([
                    'dosen_id' => $pengampu->dosen_id,
                    'dosen_nama' => $pengampu->dosen->nama, 
                ]);
            } else {
                Log::info('getDosenPengampu: Pengampu atau Dosen tidak ditemukan untuk Mata Kuliah ID: ' . $mataKuliahId . ', Kelas: ' . $kelas);
                return response()->json(['dosen_id' => null, 'message' => 'Dosen pengampu tidak ditemukan.']);
            }
        } catch (\Exception $e) {
            Log::error('Error in getDosenPengampu for Mata Kuliah ID: ' . $mataKuliahId . ', Kelas: ' . $kelas . ': ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data dosen pengampu.'], 500);
        }
    }
}
