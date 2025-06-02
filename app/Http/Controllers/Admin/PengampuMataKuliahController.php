<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengampuMataKuliah;
use App\Models\JadwalKuliah; // <-- Pastikan model JadwalKuliah diimport
use App\Models\MataKuliah;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PengampuMataKuliahController extends Controller
{
    public function index(Request $request)
    {
        // Tetap seperti sebelumnya, ini akan menampilkan daftar penugasan dosen
        $filterKelas = $request->input('kelas');
        $filterDosen = $request->input('dosen');
        $filterMataKuliah = $request->input('mata_kuliah');

        $query = PengampuMataKuliah::query();

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
        $pengampuMataKuliah = $query->get();

        $dayOrder = [
            'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4,
            'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7,
        ];

        $sortedPengampuMataKuliah = $pengampuMataKuliah->sort(function ($a, $b) use ($dayOrder) {
            $orderA = $dayOrder[$a->hari] ?? 99;
            $orderB = $dayOrder[$b->hari] ?? 99;

            if ($orderA === $orderB) {
                return Carbon::parse($a->jam_mulai)->timestamp <=> Carbon::parse($b->jam_mulai)->timestamp;
            }

            return $orderA <=> $orderB;
        });

        // Mengembalikan view daftar penugasan dosen (asumsi: admin.pengampu_mata_kuliah.index.blade.php)
        return view('admin.pengampu_mata_kuliah.index', compact('sortedPengampuMataKuliah'));
    }

    public function create()
    {
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        $dosens = Dosen::orderBy('nama')->get();
        $haris = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        // Mengembalikan view form tambah penugasan
        return view('admin.pengampu_mata_kuliah.create', compact('mataKuliahs', 'dosens', 'haris', 'kelasOptions'));
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
            // Validasi bentrok di pengampu_mata_kuliahs
            'unique_pengampu_mata_kuliah_ruangan' => [
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
            // Tambahkan validasi bentrok juga untuk tabel jadwal_kuliahs
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
        ], [
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'unique_pengampu_mata_kuliah_ruangan.unique' => 'Jadwal penugasan di ruangan tersebut sudah terisi pada waktu yang sama.',
            'unique_jadwal_kuliah_ruangan.unique' => 'Jadwal kuliah di ruangan tersebut sudah terisi pada waktu yang sama.'
        ]);

        // Simpan ke tabel pengampu_mata_kuliahs
        $newPengampu = PengampuMataKuliah::create($request->all());

        // Sinkronkan ke tabel jadwal_kuliahs
        // Pastikan kolom-kolomnya sama persis dengan yang ada di tabel jadwal_kuliahs
        JadwalKuliah::create([
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'dosen_id' => $request->dosen_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'ruangan' => $request->ruangan,
            'kelas' => $request->kelas,
            // Anda mungkin ingin menyimpan ID dari pengampu_mata_kuliah sebagai referensi jika diperlukan
            // 'pengampu_id' => $newPengampu->id,
        ]);

        return redirect()->route('admin.pengampuMataKuliah.index')->with('success', 'Penugasan dosen pengampu berhasil ditambahkan dan jadwal kuliah disinkronkan!');
    }

    public function show(PengampuMataKuliah $pengampuMataKuliah)
    {
        return redirect()->route('admin.pengampuMataKuliah.edit', $pengampuMataKuliah);
    }

    public function edit(PengampuMataKuliah $pengampuMataKuliah)
    {
        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        $dosens = Dosen::orderBy('nama')->get();
        $haris = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010'];
        // Mengembalikan view form edit penugasan
        return view('admin.pengampu_mata_kuliah.edit', compact('pengampuMataKuliah', 'mataKuliahs', 'dosens', 'haris', 'kelasOptions'));
    }

    public function update(Request $request, PengampuMataKuliah $pengampuMataKuliah)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'ruangan' => 'required|string|max:100',
            'kelas' => 'nullable|string|max:10',
            // Validasi bentrok di pengampu_mata_kuliahs (abaikan ID saat ini)
            'unique_pengampu_mata_kuliah_ruangan' => [
                'nullable',
                Rule::unique('pengampu_mata_kuliahs')->where(function ($query) use ($request) {
                    return $query->where('hari', $request->hari)
                                 ->where('ruangan', $request->ruangan)
                                 ->where(function ($q) use ($request) {
                                     $q->where('jam_mulai', '<', $request->jam_selesai)
                                       ->orWhere('jam_selesai', '>', $request->jam_mulai);
                                 });
                })->ignore($pengampuMataKuliah->id),
            ],
            // Validasi bentrok di jadwal_kuliahs (abaikan ID yang terkait jika ada cara menemukannya)
            // Ini akan sedikit rumit karena tidak ada ID langsung dari PengampuMataKuliah ke JadwalKuliah
            // Anda harus mencari entri JadwalKuliah yang sesuai dengan pengampuMataKuliah yang sedang diupdate
            'unique_jadwal_kuliah_ruangan' => [
                'nullable',
                // Cari entri JadwalKuliah yang terkait dengan pengampuMataKuliah ini
                // Jika Anda punya kolom referensi di jadwal_kuliahs, gunakan itu.
                // Jika tidak, Anda harus mencari berdasarkan semua kolom lain
                Rule::unique('jadwal_kuliahs')->where(function ($query) use ($request, $pengampuMataKuliah) {
                    return $query->where('hari', $request->hari)
                                 ->where('ruangan', $request->ruangan)
                                 ->where('mata_kuliah_id', $pengampuMataKuliah->mata_kuliah_id) // Tambahan untuk identifikasi yang lebih baik
                                 ->where('dosen_id', $pengampuMataKuliah->dosen_id) // Tambahan untuk identifikasi yang lebih baik
                                 ->where('kelas', $pengampuMataKuliah->kelas) // Tambahan untuk identifikasi yang lebih baik
                                 ->where(function ($q) use ($request) {
                                     $q->where('jam_mulai', '<', $request->jam_selesai)
                                       ->orWhere('jam_selesai', '>', $request->jam_mulai);
                                 })
                                 // Ini bagian paling sulit, bagaimana mengabaikan ID JadwalKuliah yang *sesuai* dengan $pengampuMataKuliah ini?
                                 // Tanpa kolom referensi, ini harus mencari berdasarkan semua kolom.
                                 // Contoh: Jika ada kolom 'pengampu_id' di jadwal_kuliahs
                                 // ->where('pengampu_id', '!=', $pengampuMataKuliah->id);
                                 // Karena tidak ada ID langsung, kita tidak bisa ignore ID JadwalKuliah secara langsung di sini.
                                 // Solusi manual yang lebih baik ada di luar validasi ini.
                                 ->whereDoesntHave('pengampuMataKuliah', function ($q) use ($pengampuMataKuliah) {
                                     $q->where('id', $pengampuMataKuliah->id);
                                 }); // Ini hanya bekerja jika JadwalKuliah memiliki relasi belongsTo ke PengampuMataKuliah
                }),
            ],
        ], [
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'unique_pengampu_mata_kuliah_ruangan.unique' => 'Jadwal penugasan di ruangan tersebut sudah terisi pada waktu yang sama.',
            'unique_jadwal_kuliah_ruangan.unique' => 'Jadwal kuliah di ruangan tersebut sudah terisi pada waktu yang sama.'
        ]);

        // Perbarui di tabel pengampu_mata_kuliahs
        $pengampuMataKuliah->update($request->all());

        // Sinkronkan ke tabel jadwal_kuliahs
        // CARI entri yang sesuai di jadwal_kuliahs dan perbarui.
        // Ini adalah bagian kritis yang memerlukan cara untuk mengidentifikasi baris yang sama.
        // Cara terbaik adalah dengan memiliki kolom `pengampu_mata_kuliah_id` di tabel `jadwal_kuliahs`
        // atau mencari berdasarkan semua kolom yang cocok.
        JadwalKuliah::where('mata_kuliah_id', $pengampuMataKuliah->mata_kuliah_id)
                    ->where('dosen_id', $pengampuMataKuliah->dosen_id)
                    ->where('hari', $pengampuMataKuliah->hari)
                    ->where('jam_mulai', $pengampuMataKuliah->jam_mulai)
                    ->where('jam_selesai', $pengampuMataKuliah->jam_selesai)
                    ->where('ruangan', $pengampuMataKuliah->ruangan)
                    ->where('kelas', $pengampuMataKuliah->kelas)
                    ->update($request->all()); // Ini akan memperbarui semua kolom

        return redirect()->route('admin.pengampuMataKuliah.index')->with('success', 'Penugasan dosen pengampu berhasil diperbarui dan jadwal kuliah disinkronkan!');
    }

    public function destroy(PengampuMataKuliah $pengampuMataKuliah)
    {
        // Hapus dari tabel pengampu_mata_kuliahs
        $pengampuMataKuliah->delete();

        // Sinkronkan: Hapus juga dari tabel jadwal_kuliahs
        JadwalKuliah::where('mata_kuliah_id', $pengampuMataKuliah->mata_kuliah_id)
                    ->where('dosen_id', $pengampuMataKuliah->dosen_id)
                    ->where('hari', $pengampuMataKuliah->hari)
                    ->where('jam_mulai', $pengampuMataKuliah->jam_mulai)
                    ->where('jam_selesai', $pengampuMataKuliah->jam_selesai)
                    ->where('ruangan', $pengampuMataKuliah->ruangan)
                    ->where('kelas', $pengampuMataKuliah->kelas)
                    ->delete();

        return redirect()->route('admin.pengampuMataKuliah.index')->with('success', 'Penugasan dosen pengampu berhasil dihapus dan jadwal kuliah disinkronkan!');
    }
}