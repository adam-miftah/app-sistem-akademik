<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah; 
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request) 
    {

        $filterKodeMk = $request->input('kode_mk');
        $filterNamaMk = $request->input('nama_mk');
        $filterSKS = $request->input('sks');
        $query = MataKuliah::query();

        if (!empty($filterKodeMk)) {
            $query->where('kode_mk', 'like', '%' . $filterKodeMk . '%');
        }

        if (!empty($filterNamaMk)) {
            $query->where('nama_mk', 'like', '%' . $filterNamaMk . '%');
        }

        if (!empty($filterSKS)) {
            $query->where('sks', (int)$filterSKS);
        }

        $mataKuliahs = $query->orderBy('nama_mk')->get();
        return view('admin.mata_kuliah.index', compact('mataKuliahs'));
    }

    public function create()
    {
        return view('admin.mata_kuliah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:20|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'kelas' => 'required|string|in:Reguler A, Reguler B, Reguler CK, Reguler CS', 
            'deskripsi' => 'nullable|string',
        ]);

        MataKuliah::create($request->all());
        return redirect()->route('admin.mataKuliahs.index')->with('success', 'Data mata kuliah berhasil ditambahkan!');
    }

    public function show(MataKuliah $mataKuliah)
    {
        return redirect()->route('admin.mataKuliahs.edit', $mataKuliah);
    }

    public function edit(MataKuliah $mataKuliah)
    {
        return view('admin.mata_kuliah.edit', compact('mataKuliah'));
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:20|unique:mata_kuliahs,kode_mk,' . $mataKuliah->id,
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'kelas' => 'required|string|in:Reguler,Khusus,Pagi,Malam', 
            'deskripsi' => 'nullable|string',
        ]);

        $mataKuliah->update($request->all());
        return redirect()->route('admin.mataKuliahs.index')->with('success', 'Data mata kuliah berhasil diperbarui!');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        $mataKuliah->delete();
        return redirect()->route('admin.mataKuliahs.index')->with('success', 'Data mata kuliah berhasil dihapus!');
    }
}
