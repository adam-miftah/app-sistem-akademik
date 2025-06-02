<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKuliah;
use App\Models\PengampuMataKuliah; // Pastikan model PengampuMataKuliah diimport
use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class JadwalKuliahController extends Controller
{
    public function index(Request $request)
    {
        $filterKelas = $request->input('kelas');
        $filterDosen = $request->input('dosen');
        $filterMataKuliah = $request->input('mata_kuliah');

        $query = JadwalKuliah::query();

        if (!empty($filterKelas)) {
            $query->where('kelas', 'like', '%' . $filterKelas . '%');
        }

        if (!empty($filterDosen)) {
            $query->whereHas('dosen', function ($q) use ($filterDosen) {
                $q->where('nama', 'like', '%' . $filterDosen . '%');
            });
        }

        if (!empty($filterMataKuliah)) {
            $query->whereHas('mataKuliah', function ($q) use ($filterMataKuliah) {
                $q->where('nama_mk', 'like', '%' . $filterMataKuliah . '%');
            });
        }

        $query->with(['mataKuliah', 'dosen']);
        $jadwalKuliahs = $query->get();

        $dayOrder = [
            'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4,
            'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7,
        ];

        $sortedJadwalKuliahs = $jadwalKuliahs->sort(function ($a, $b) use ($dayOrder) {
            $orderA = $dayOrder[$a->hari] ?? 99;
            $orderB = $dayOrder[$b->hari] ?? 99;

            if ($orderA === $orderB) {
                return Carbon::parse($a->jam_mulai)->timestamp <=> Carbon::parse($b->jam_mulai)->timestamp;
            }

            return $orderA <=> $orderB;
        });

        return view('admin.jadwal_kuliah.index', compact('sortedJadwalKuliahs'));
    }

    public function create()
    {
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        $dosens = Dosen::orderBy('nama')->get();
        $haris = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        return view('admin.jadwal_kuliah.create', compact('mataKuliahs', 'dosens', 'haris', 'kelasOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required|string|max:100',
            'kelas' => 'nullable|string|max:10',
            'unique_jadwal_kuliah_ruangan' => [
                'nullable',
                Rule::unique('jadwal_kuliahs')->where(function ($query) use ($request) {
                    return $query->where('hari', $request->hari)
                                 ->where('ruangan', $request->ruangan)
                                 ->where(function ($q) use ($request) {
                                     $q->where('jam_mulai', '<', $request->jam_selesai)
                                       ->orWhere('jam_selesai', '>', $request->jam_mulai);
                                 });
                }),
            ],
            'unique_pengampu_mata_kuliah_ruangan_for_sync' => [
                'nullable',
                Rule::unique('pengampu_mata_kuliahs')->where(function ($query) use ($request) {
                    return $query->where('hari', $request->hari)
                                 ->where('ruangan', $request->ruangan)
                                 ->where(function ($q) use ($request) {
                                     $q->where('jam_mulai', '<', $request->jam_selesai)
                                       ->orWhere('jam_selesai', '>', $request->jam_mulai);
                                 });
                }),
            ],
        ], [
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'unique_jadwal_kuliah_ruangan.unique' => 'Jadwal kuliah di ruangan tersebut sudah terisi pada waktu yang sama.',
            'unique_pengampu_mata_kuliah_ruangan_for_sync.unique' => 'Jadwal penugasan di ruangan tersebut sudah terisi pada waktu yang sama (dari jadwal kuliah).'
        ]);

        $newJadwal = JadwalKuliah::create($request->all());

        PengampuMataKuliah::create([
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'dosen_id' => $request->dosen_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'ruangan' => $request->ruangan,
            'kelas' => $request->kelas,
        ]);

        return redirect()->route('admin.jadwalKuliahs.index')->with('success', 'Jadwal Kuliah berhasil ditambahkan dan penugasan disinkronkan!');
    }

    public function show(JadwalKuliah $jadwalKuliah)
    {
        return redirect()->route('admin.jadwalKuliahs.edit', $jadwalKuliah);
    }

    public function edit(JadwalKuliah $jadwalKuliah)
    {
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        $dosens = Dosen::orderBy('nama')->get();
        $haris = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        return view('admin.jadwal_kuliah.edit', compact('jadwalKuliah', 'mataKuliahs', 'dosens', 'haris', 'kelasOptions'));
    }

    public function update(Request $request, JadwalKuliah $jadwalKuliah)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required|string|max:100',
            'kelas' => 'nullable|string|max:10',
            'unique_jadwal_kuliah_ruangan' => [
                'nullable',
                Rule::unique('jadwal_kuliahs')->where(function ($query) use ($request) {
                    return $query->where('hari', $request->hari)
                                 ->where('ruangan', $request->ruangan)
                                 ->where(function ($q) use ($request) {
                                     $q->where('jam_mulai', '<', $request->jam_selesai)
                                       ->orWhere('jam_selesai', '>', $request->jam_mulai);
                                 });
                })->ignore($jadwalKuliah->id),
            ],
            'unique_pengampu_mata_kuliah_ruangan_for_sync' => [
                'nullable',
                Rule::unique('pengampu_mata_kuliahs')->where(function ($query) use ($request, $jadwalKuliah) {
                    return $query->where('hari', $request->hari)
                                 ->where('ruangan', $request->ruangan)
                                 ->where('mata_kuliah_id', $jadwalKuliah->mata_kuliah_id)
                                 ->where('dosen_id', $jadwalKuliah->dosen_id)
                                 ->where('kelas', $jadwalKuliah->kelas)
                                 ->where(function ($q) use ($request) {
                                     $q->where('jam_mulai', '<', $request->jam_selesai)
                                       ->orWhere('jam_selesai', '>', $request->jam_mulai);
                                 });
                     $existingPengampuToIgnore = PengampuMataKuliah::where('mata_kuliah_id', $jadwalKuliah->mata_kuliah_id)
                        ->where('dosen_id', $jadwalKuliah->dosen_id)
                        ->where('hari', $jadwalKuliah->hari)
                        ->where('jam_mulai', $jadwalKuliah->jam_mulai)
                        ->where('jam_selesai', $jadwalKuliah->jam_selesai)
                        ->where('ruangan', $jadwalKuliah->ruangan)
                        ->where('kelas', $jadwalKuliah->kelas)
                        ->first();
                    if ($existingPengampuToIgnore) {
                        $query->where('id', '!=', $existingPengampuToIgnore->id);
                    }
                    return $query;
                }),
            ],
        ], [
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'unique_jadwal_kuliah_ruangan.unique' => 'Jadwal kuliah di ruangan tersebut sudah terisi pada waktu yang sama.',
            'unique_pengampu_mata_kuliah_ruangan_for_sync.unique' => 'Jadwal penugasan di ruangan tersebut sudah terisi pada waktu yang sama (dari jadwal kuliah).'
        ]);

        $jadwalKuliah->update($request->all());

        PengampuMataKuliah::where('mata_kuliah_id', $jadwalKuliah->mata_kuliah_id)
                          ->where('dosen_id', $jadwalKuliah->dosen_id)
                          ->where('hari', $jadwalKuliah->hari)
                          ->where('jam_mulai', $jadwalKuliah->jam_mulai)
                          ->where('jam_selesai', $jadwalKuliah->jam_selesai)
                          ->where('ruangan', $jadwalKuliah->ruangan)
                          ->where('kelas', $jadwalKuliah->kelas)
                          ->update($request->all());

        return redirect()->route('admin.jadwalKuliahs.index')->with('success', 'Jadwal Kuliah berhasil diperbarui dan penugasan disinkronkan!');
    }

    public function destroy(JadwalKuliah $jadwalKuliah)
    {
        $jadwalKuliah->delete();

        PengampuMataKuliah::where('mata_kuliah_id', $jadwalKuliah->mata_kuliah_id)
                          ->where('dosen_id', $jadwalKuliah->dosen_id)
                          ->where('hari', $jadwalKuliah->hari)
                          ->where('jam_mulai', $jadwalKuliah->jam_mulai)
                          ->where('jam_selesai', $jadwalKuliah->jam_selesai)
                          ->where('ruangan', $jadwalKuliah->ruangan)
                          ->where('kelas', $jadwalKuliah->kelas)
                          ->delete();

        return redirect()->route('admin.jadwalKuliahs.index')->with('success', 'Jadwal Kuliah berhasil dihapus dan penugasan disinkronkan!');
    }
}