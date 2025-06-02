@extends('layouts.app')

@section('title', 'Kelola Jadwal Mengajar - Admin')
@section('header_title', 'Kelola Jadwal Mengajar')

@section('content')
  <style>
    /* Notifikasi Sukses */
    .alert-success {
    background-color: #d4edda;
    color: #155724;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid #28a745;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    }

    /* Header Halaman */
    .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
    }

    .page-title {
    margin: 0;
    color: var(--text-color);
    font-size: 1.5rem;
    font-weight: 600;
    }

    /* Tombol */
    .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
    }

    .btn-primary {
    background-color: var(--primary-color);
    color: white;
    }

    .btn-primary:hover {
    background-color: var(--primary-hover);
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(67, 97, 238, 0.2);
    }

    .btn-danger:hover {
    transform: translateY(-2px);
    }

    .btn-warning:hover {
    transform: translateY(-2px);
    }

    /* Tabel Data */
    .data-table-container {
    overflow-x: auto;
    background: var(--white-bg);
    border-radius: 12px;
    box-shadow: var(--shadow-light);
    margin-bottom: 2rem;
    }

    .time-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 6px;
    background: #f0fdf9;
    color: #0d9488;
    font-weight: 500;
    }

    .ruangan-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    margin: 12px 0 0 12px;
    background: #e0f2fe;
    color: #0369a1;
    font-weight: 500;
    font-size: 0.85rem;
    }

    .data-table {
    width: 100%;
    border-collapse: collapse;
    }

    .data-table thead {
    background-color: var(--primary-color);
    color: white;
    }

    .data-table th {
    padding: 1rem;
    text-align: left;
    font-weight: 500;
    }

    .data-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    }

    .data-table tr:last-child td {
    border-bottom: none;
    }

    .data-table tr:hover {
    background-color: rgba(67, 97, 238, 0.05);
    }

    /* Tombol Aksi */
    .action-buttons {
    display: flex;
    gap: 0.5rem;
    }

    /* Tampilan Kosong */
    .empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--text-light);
    }

    .empty-state i {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--text-light);
    }

    /* Responsif untuk Tablet */
    @media (max-width: 992px) {

    .data-table td:nth-child(1),
    .data-table th:nth-child(1) {
      display: none;
    }
    }

    /* Responsif untuk Mobile */
    @media (max-width: 768px) {
    .page-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .action-buttons {
      flex-direction: column;
      gap: 0.5rem;
    }

    .btn {
      width: 100%;
      justify-content: center;
    }
    }

    @media (max-width: 576px) {

    .data-table th,
    .data-table td {
      padding: 0.75rem;
    }

    /* Sembunyikan teks tombol di mobile */
    .btn-text {
      display: none;
    }

    /* Tombol ikon saja di mobile */
    .btn i {
      margin: 0;
    }
    }

    /* Gaya untuk form filter */
    .filter-form {
    background: var(--white-bg);
    border-radius: 12px;
    box-shadow: var(--shadow-light);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: flex-end;
    /* Memastikan tombol filter sejajar dengan input */
    }

    .filter-group {
    flex: 1;
    min-width: 180px;
    /* Lebar minimum untuk setiap filter */
    }

    .filter-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-color);
    }

    .filter-group input[type="text"],
    .filter-group select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.875rem;
    color: var(--text-color);
    background-color: var(--white-bg);
    box-sizing: border-box;
    /* Pastikan padding tidak menambah lebar total */
    }

    .filter-buttons {
    display: flex;
    gap: 0.75rem;
    }

    /* Responsif untuk form filter */
    @media (max-width: 768px) {
    .filter-form {
      flex-direction: column;
      align-items: stretch;
    }

    .filter-group {
      min-width: 100%;
    }

    .filter-buttons {
      flex-direction: column;
    }
    }
  </style>

  <div class="content-area">
    @if (session('success'))
    <div class="alert-success">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
    </div>
    @endif

    {{-- Form Filter --}}
    <form action="{{ route('admin.pengampuMataKuliah.index') }}" method="GET" class="filter-form">
    <div class="filter-group">
      <label for="dosen">Nama Dosen</label>
      <input type="text" id="dosen" name="dosen" value="{{ request('dosen') }}" placeholder="Nama dosen...">
    </div>
    <div class="filter-group">
      <label for="mata_kuliah">Nama Mata Kuliah</label>
      <input type="text" id="mata_kuliah" name="mata_kuliah" value="{{ request('mata_kuliah') }}"
      placeholder="Nama mata kuliah...">
    </div>
    <div class="filter-group">
      <label for="kelas">Kelas</label>
      <input type="text" id="kelas" name="kelas" value="{{ request('kelas') }}" placeholder="Cari Kelas..">
    </div>
    <div class="filter-buttons">
      <button type="submit" class="btn btn-primary">
      <i class="fas fa-filter"></i> <span class="btn-text">Filter</span>
      </button>
      <a href="{{ route('admin.pengampuMataKuliah.index') }}" class="btn btn-secondary"
      style="background-color: #6c757d; color: white;">
      <i class="fas fa-sync-alt"></i> <span class="btn-text">Reset</span>
      </a>
    </div>
    </form>

    <div class="page-header">
    <h3 class="page-title">Daftar Penugasan Dosen Pengampu Mata Kuliah</h3>
    <a href="{{ route('admin.pengampuMataKuliah.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i>
      <span class="btn-text">Tambah Penugasan</span>
    </a>
    </div>

    <div class="data-table-container">
    <table class="data-table">
      <thead>
      <tr>
        <th>ID</th>
        <th>Dosen Pengampu</th>
        <th>Mata Kuliah</th>
        <th>Hari</th>
        <th>Jam </th>
        <th>Ruangan</th>
        <th>Kelas</th>
        <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
      @forelse ($sortedPengampuMataKuliah as $pengampu)
      <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $pengampu->dosen->nama }}</td>
      <td>{{ $pengampu->mataKuliah->nama_mk }}</td>
      <td>{{ $pengampu->hari }}</td>
      <td>
      <span class="time-badge">
        <i class="far fa-clock"></i>
        {{ \Carbon\Carbon::parse($pengampu->jam_mulai)->format('H:i') }} -
        {{ \Carbon\Carbon::parse($pengampu->jam_selesai)->format('H:i') }}
      </span>
      </td>
      <td class="ruangan-badge">{{ $pengampu->ruangan }}</td>
      <td>{{ $pengampu->kelas ?? '-' }}</td>
      <td>
      <div class="action-buttons">
        <a href="{{ route('admin.pengampuMataKuliah.edit', $pengampu->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i>
        <span class="btn-text">Edit</span>
        </a>
        <form action="{{ route('admin.pengampuMataKuliah.destroy', $pengampu->id) }}" method="POST"
        style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"
        onclick="return confirm('Apakah Anda yakin ingin menghapus penugasan ini?')">
        <i class="fas fa-trash-alt"></i>
        <span class="btn-text">Hapus</span>
        </button>
        </form>
      </div>
      </td>
      </tr>
    @empty
      <tr>
      <td colspan="9" class="empty-state">
      <i class="fas fa-chalkboard-teacher"></i>
      <p>Tidak ada data penugasan dosen pengampu</p>
      </td>
      </tr>
    @endforelse
      </tbody>
    </table>
    </div>
  </div>
@endsection