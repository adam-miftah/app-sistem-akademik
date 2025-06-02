<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsenDosen;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AbsenDosenController extends Controller
{
    public function index(Request $request)
    {
        $filterDosenNama = $request->input('dosen_nama');
        $query = AbsenDosen::query();
        $query->with('dosen');

        if (!empty($filterDosenNama)) {
            $query->whereHas('dosen', function ($q) use ($filterDosenNama) {
                $q->where('nama', 'like', '%' . $filterDosenNama . '%');
            });
        }
    $absenDosens = $query->orderBy('tanggal', 'desc')
                             ->orderBy('dosen_id')
                             ->paginate(10); 
        return view('admin.absen_dosen.index', compact('absenDosens'));        
    }

    public function create()
    {
        $dosens = Dosen::orderBy('nama')->get();
        return view('admin.absen_dosen.create', compact('dosens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'tanggal' => 'required|date',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i|after_or_equal:waktu_masuk',
            'status' => ['required', Rule::in(['Hadir', 'Izin', 'Sakit', 'Alpha'])],
            'keterangan' => 'nullable|string|max:255',
        ]);

        AbsenDosen::create($validated);

        return redirect()->route('admin.absen_dosen.index')
                         ->with('success', 'Absen dosen berhasil ditambahkan.');
    }

    public function show(AbsenDosen $absenDosen)
    {
        abort(404); 
    }

    public function edit(AbsenDosen $absenDosen)
    {
        $dosens = Dosen::orderBy('nama')->get();
        return view('admin.absen_dosen.edit', compact('absenDosen', 'dosens'));
    }

    public function update(Request $request, AbsenDosen $absenDosen)
    {
        $validated = $request->validate([
            'dosen_id' => 'required|exists:dosens,id',
            'tanggal' => 'required|date',
            'waktu_masuk' => 'nullable|date_format:H:i',
            'waktu_keluar' => 'nullable|date_format:H:i|after_or_equal:waktu_masuk',
            'status' => ['required', Rule::in(['Hadir', 'Izin', 'Sakit', 'Alpha'])],
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absenDosen->update($validated);

        return redirect()->route('admin.absen_dosen.index')
                         ->with('success', 'Absen dosen berhasil diperbarui.');
    }

    public function destroy(AbsenDosen $absenDosen)
    {
        $absenDosen->delete();
        return redirect()->route('admin.absen_dosen.index')
                         ->with('success', 'Absen dosen berhasil dihapus.');
    }
}