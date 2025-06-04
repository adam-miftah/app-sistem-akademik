<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PresensiMahasiswa;
use App\Models\MataKuliah;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\PengampuMataKuliah;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 

class KelolaPresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PresensiMahasiswa::with([
            'mahasiswa', 
            'pengampuMataKuliah.mataKuliah', 
            'pengampuMataKuliah.dosen'
        ])->latest('tanggal');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', Carbon::parse($request->tanggal_mulai)->toDateString());
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', Carbon::parse($request->tanggal_selesai)->toDateString());
        }
        if ($request->filled('mata_kuliah_id')) {
            $query->whereHas('pengampuMataKuliah', function ($q) use ($request) {
                $q->where('mata_kuliah_id', $request->mata_kuliah_id);
            });
        }
        if ($request->filled('kelas')) {
            $query->whereHas('pengampuMataKuliah', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }
        if ($request->filled('mahasiswa_id')) {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }
        if ($request->filled('dosen_id')) {
            $query->whereHas('pengampuMataKuliah', function ($q) use ($request) {
                $q->where('dosen_id', $request->dosen_id);
            });
        }
        // Filter berdasarkan Status Kehadiran (Hadir atau Tidak Hadir)
        if ($request->filled('status_kehadiran')) {
            // Jika yang dipilih "Tidak Hadir", kita mungkin ingin mencakup semua yang bukan "Hadir"
            // atau hanya yang secara eksplisit "Tidak Hadir" atau "Alpha".
            // Untuk kesederhanaan, kita filter berdasarkan nilai yang dipilih.
            // Jika Anda ingin "Tidak Hadir" mencakup "Alpha", "Izin", "Sakit", logikanya perlu disesuaikan.
            // Saat ini, jika admin memilih "Tidak Hadir", maka hanya record dengan status "Tidak Hadir" yang akan muncul.
            $query->where('status_kehadiran', $request->status_kehadiran);
        }

        $presensiRecords = $query->paginate(15)->withQueryString();

        $mataKuliahs = MataKuliah::orderBy('nama_mk')->get();
        $kelasOptions = PengampuMataKuliah::distinct()->orderBy('kelas')->pluck('kelas');
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        $dosens = Dosen::orderBy('nama')->get();

        return view('admin.kelola_presensi.index', compact(
            'presensiRecords', 
            'mataKuliahs', 
            'kelasOptions',
            'mahasiswas',
            'dosens',
            'request'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PresensiMahasiswa $presensiMahasiswa)
    {
        $presensiMahasiswa->load(['mahasiswa', 'pengampuMataKuliah.mataKuliah', 'pengampuMataKuliah.dosen']);
        return view('admin.kelola_presensi.edit', compact('presensiMahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PresensiMahasiswa $presensiMahasiswa)
    {
        $request->validate([
            // Validasi hanya untuk Hadir dan Tidak Hadir
            'status_kehadiran' => 'required|string|in:Hadir,Tidak Hadir', 
        ]);

        $presensiMahasiswa->status_kehadiran = $request->status_kehadiran;
        $presensiMahasiswa->save();

        return redirect()->route('admin.kelolaPresensi.index')
                         ->with('success', 'Status presensi mahasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PresensiMahasiswa $presensiMahasiswa)
    {
        try {
            $presensiMahasiswa->delete();
            return redirect()->route('admin.kelolaPresensi.index')
                             ->with('success', 'Data presensi berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Error deleting presensi record: " . $e->getMessage());
            return redirect()->route('admin.kelolaPresensi.index')
                             ->with('error', 'Gagal menghapus data presensi. Silakan coba lagi.');
        }
    }
}
