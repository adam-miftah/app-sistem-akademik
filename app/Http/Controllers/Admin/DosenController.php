<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen; 
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 

class DosenController extends Controller
{
    public function index(Request $request)
    {
         $filterNama = $request->input('nama');
        $filterNIDN = $request->input('nidn');
        $filterProdi = $request->input('prodi');

        // Mulai query Dosen
        $query = Dosen::query();

        if (!empty($filterNama)) {
            $query->where('nama', 'like', '%' . $filterNama . '%');
        }

        if (!empty($filterNIDN)) {
            $query->where('nidn', 'like', '%' . $filterNIDN . '%');
        }

        if (!empty($filterProdi)) {
            $query->where('prodi', 'like', '%' . $filterProdi . '%');
        }

        $dosens = $query->orderBy('nama')->get();

        return view('admin.dosen.index', compact('dosens'));
    }

    public function create()
    {
        return view('admin.dosen.create');
    }

    public function store(Request $request)
    {
        // Validasi input untuk User dan Dosen
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,email|max:255', 
            'password' => 'required|string|min:8|confirmed', 

            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email|max:255', 
            'nidn' => 'nullable|string|unique:dosens,nidn|max:20', 
            'prodi' => 'nullable|string|max:255',
        ]);

        try {
            $user = User::create([
                'name' => $request->user_name,
                'email' => $request->user_email,
                'password' => Hash::make($request->password),
                'role' => 'dosen', 
            ]);

            Dosen::create([
                'user_id' => $user->id, 
                'nama' => $request->nama,
                'email' => $request->email,
                'nidn' => $request->nidn,
                'prodi' => $request->prodi,
            ]);

            return redirect()->route('admin.dosens.index')->with('success', 'Akun dosen dan profil berhasil ditambahkan!');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan dosen: ' . $e->getMessage());
        }
    }

    public function show(Dosen $dosen)
    {
        return redirect()->route('admin.dosens.edit', $dosen);
    }

    public function edit(Dosen $dosen)
    {
        $dosen->load('user');
        return view('admin.dosen.edit', compact('dosen'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:dosens,email,' . $dosen->id, // Email unik, kecuali untuk dirinya sendiri
            'nidn' => 'nullable|string|unique:dosens,nidn,' . $dosen->id . '|max:20',
            'prodi' => 'nullable|string|max:255',
        ]);

        $dosen->update($request->only(['nama', 'email', 'nidn', 'prodi']));
        return redirect()->route('admin.dosens.index')->with('success', 'Data dosen berhasil diperbarui!');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->delete(); 
        return redirect()->route('admin.dosens.index')->with('success', 'Data dosen berhasil dihapus!');
    }
}
