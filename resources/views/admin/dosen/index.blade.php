@extends('layouts.app')

@section('title', 'Kelola Data Dosen - Admin')
@section('header_title', 'Kelola Data Dosen')

@section('content')
  <style>
    /* Success Notification */
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

    /* Page Header */
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

    /* Buttons */
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

    .btn-warning {
    color: var(--text-color);
    }

    .btn-warning:hover {
    transform: translateY(-2px);
    }

    .btn-danger {
    background-color: var(--danger-color);
    color: white;
    }

    .btn-danger:hover {
    background-color: #e3174a;
    transform: translateY(-2px);
    }

    /* Table Styles */
    .data-table-container {
    overflow-x: auto;
    background: var(--white-bg);
    border-radius: 12px;
    box-shadow: var(--shadow-light);
    margin-bottom: 2rem;
    }

    .data-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px;
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

    /* Action Buttons */
    .action-buttons {
    display: flex;
    gap: 0.5rem;
    }

    /* Empty State */
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

    /* Responsive Adjustments */
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

    .btn-text {
      display: none;
    }

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
    <form action="{{ route('admin.dosens.index') }}" method="GET" class="filter-form">
    <div class="filter-group">
      <label for="nidn">NIDN</label>
      <input type="text" id="nidn" name="nidn" value="{{ request('nidn') }}" placeholder="Cari NIDN...">
    </div>
    <div class="filter-group">
      <label for="nama">Nama Dosen</label>
      <input type="text" id="nama" name="nama" value="{{ request('nama') }}" placeholder="Cari nama dosen...">
    </div>
    {{-- <div class="filter-group">
      <label for="prodi">Program Studi:</label> --}}
      {{-- Anda bisa menggunakan select jika prodi sudah terdefinisi dan tetap --}}
      {{-- <input type="text" id="prodi" name="prodi" value="{{ request('prodi') }}"
      placeholder="Cari program studi...">
    </div> --}}
    <div class="filter-buttons">
      <button type="submit" class="btn btn-primary">
      <i class="fas fa-filter"></i> <span class="btn-text">Filter</span>
      </button>
      <a href="{{ route('admin.dosens.index') }}" class="btn btn-secondary"
      style="background-color: #6c757d; color: white;">
      <i class="fas fa-sync-alt"></i> <span class="btn-text">Reset</span>
      </a>
    </div>
    </form>

    <div class="page-header">
    <h3 class="page-title">Daftar Dosen</h3>
    <a href="{{ route('admin.dosens.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i>
      <span class="btn-text">Tambah Dosen Baru</span>
    </a>
    </div>

    <div class="data-table-container">
    <table class="data-table">
      <thead>
      <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>NIDN</th>
        <th>Program Studi</th>
        <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
      @forelse ($dosens as $dosen)
      <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $dosen->nama }}</td>
      <td>{{ $dosen->email }}</td>
      <td>{{ $dosen->nidn ?? '-' }}</td>
      <td>{{ $dosen->prodi ?? '-' }}</td>
      <td>
      <div class="action-buttons">
        <a href="{{ route('admin.dosens.edit', $dosen->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i>
        <span class="btn-text">Edit</span>
        </a>
        <form action="{{ route('admin.dosens.destroy', $dosen->id) }}" method="POST"
        style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" style="background: var(--bs-danger);"
        onclick="return confirm('Apakah Anda yakin ingin menghapus data dosen ini?')">
        <i class="fas fa-trash-alt"></i>
        <span class="btn-text">Hapus</span>
        </button>
        </form>
      </div>
      </td>
      </tr>
    @empty
      <tr>
      <td colspan="6" class="empty-state">
      <i class="fas fa-user-slash"></i>
      <p>Tidak ada data dosen.</p>
      </td>
      </tr>
    @endforelse
      </tbody>
    </table>
    </div>
  </div>
@endsection