<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $filterNIM = $request->input('mahasiswa_nim');
        $filterMahasiswaNama = $request->input('mahasiswa_nama');

        $query = Mahasiswa::query();

        if (!empty($filterNIM)) {
            $query->where('nim', 'like', '%' . $filterNIM . '%');
        }

        if (!empty($filterMahasiswaNama)) {
            $query->where('nama', 'like', '%' . $filterMahasiswaNama . '%'); 
        }

        $mahasiswas = $query->orderBy('nama')->get();
        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
       
        $programStudiOptions = ['Teknik Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi']; // Sesuaikan
        $progPerkuliahanOptions = ['Reguler A', 'Reguler B', 'REGULER CK', 'REGULER CS']; // Sesuaikan
        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010']; // Sesuaikan
        $statusMahasiswaOptions = ['Aktif', 'Cuti', 'Lulus', 'Non-Aktif', 'Drop Out']; // Sesuaikan

        return view('admin.mahasiswa.create', compact('programStudiOptions', 'progPerkuliahanOptions', 'kelasOptions', 'statusMahasiswaOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswas,nim',
            'nama' => 'required|string|max:255',
            'jurusan' => 'required|string|max:100',
            'angkatan' => 'required|string|max:4',
            'tanggal_lahir' => 'nullable|date',
            'program_studi' => 'nullable|string|max:255',
            'prog_perkuliahan' => 'nullable|string|max:100',
            'kelas' => 'nullable|string|max:10',
            'status_mahasiswa' => 'required|string|in:Aktif,Cuti,Lulus,Non-Aktif,Drop Out',
            'email' => 'required|string|email|max:255|unique:users,email|unique:mahasiswas,email',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed', 
        ]);

        try {
            $user = User::create([
                'name' => $request->nama, 
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'mahasiswa', 
            ]);

            $mahasiswa = new Mahasiswa($request->except(['password', 'password_confirmation'])); 
            $mahasiswa->user_id = $user->id;
            $mahasiswa->save();

            return redirect()->route('admin.mahasiswas.index')->with('success', 'Data mahasiswa dan akun login berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan mahasiswa: ' . $e->getMessage());
        }
    }

    public function show(Mahasiswa $mahasiswa)
    {
        return redirect()->route('admin.mahasiswas.edit', $mahasiswa);
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('user');
        $programStudiOptions = ['Teknik Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi'];
        $progPerkuliahanOptions = ['Reguler A', 'Reguler B', 'REGULER CK', 'REGULER CS']; // Sesuaikan
        $kelasOptions = ['06TPLP001', '06TPLP002', '06TPLP003', '06TPLP004', '06TPLP005', '06TPLP006', '06TPLP007', '06TPLP008', '06TPLP009', '06TPLP0010']; // Sesuaikan
        $statusMahasiswaOptions = ['Aktif', 'Cuti', 'Lulus', 'Non-Aktif', 'Drop Out'];

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'programStudiOptions', 'progPerkuliahanOptions', 'kelasOptions', 'statusMahasiswaOptions'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nim' => ['required', 'string', 'max:20', Rule::unique('mahasiswas')->ignore($mahasiswa->id)],
            'nama' => 'required|string|max:255',
            'jurusan' => 'required|string|max:100',
            'angkatan' => 'required|string|max:4',
            'tanggal_lahir' => 'nullable|date',
            'program_studi' => 'nullable|string|max:255',
            'prog_perkuliahan' => 'nullable|string|max:100',
            'kelas' => 'nullable|string|max:10',
            'status_mahasiswa' => 'required|string|in:Aktif,Cuti,Lulus,Non-Aktif,Drop Out',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($mahasiswa->user_id), Rule::unique('mahasiswas')->ignore($mahasiswa->id)],
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($mahasiswa->user) {
            $mahasiswa->user->update([
                'name' => $request->nama,
                'email' => $request->email,
            ]);

            if ($request->filled('new_password')) {
                $mahasiswa->user->password = Hash::make($request->new_password);
                $mahasiswa->user->save();
            }
        }

        $mahasiswa->update($request->except(['new_password', 'new_password_confirmation']));
        $message = 'Data mahasiswa dan akun login berhasil diperbarui!';
        if ($request->filled('new_password')) {
            $message .= ' Password juga telah diubah.';
        }
        return redirect()->route('admin.mahasiswas.index')->with('success', $message);
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        return redirect()->route('admin.mahasiswas.index')->with('success', 'Data mahasiswa dan akun login berhasil dihapus!');
    }
}
