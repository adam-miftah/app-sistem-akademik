@extends('layouts.app')

@section('title', 'Kelola Data Mahasiswa - Admin')
@section('header_title', 'Kelola Data Mahasiswa')

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

    .btn-secondary {
    background-color: #6c757d;
    color: white;
    }

    .btn-secondary:hover {
    background-color: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.2);
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

    /* Tabel Data */
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
    min-width: 1200px;
    /* Increased min-width to accommodate new columns */
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
    @media (max-width: 1200px) {

    .data-table th:nth-child(7),
    /* Telepon */
    .data-table td:nth-child(7),
    .data-table th:nth-child(8),
    /* Program Studi */
    .data-table td:nth-child(8),
    .data-table th:nth-child(9),
    /* Prog. Perkuliahan */
    .data-table td:nth-child(9) {
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

    .data-table th,
    .data-table td {
      padding: 0.75rem;
    }

    /* Hide more columns for very small screens */
    .data-table th:nth-child(1),
    /* ID */
    .data-table td:nth-child(1),
    .data-table th:nth-child(6),
    /* Email */
    .data-table td:nth-child(6),
    .data-table th:nth-child(10),
    /* Kelas */
    .data-table td:nth-child(10) {
      display: none;
    }
    }

    @media (max-width: 576px) {
    .btn-text {
      display: none;
    }

    .btn i {
      margin: 0;
    }
    }

    /* Filter Form Styles */
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
    /* Ensures filter buttons align with inputs */
    }

    .filter-group {
    flex: 1;
    min-width: 160px;
    /* Adjusted min-width for more filters */
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
    /* Ensures padding doesn't add to total width */
    }

    .filter-buttons {
    display: flex;
    gap: 0.75rem;
    }

    /* Responsive adjustments for filter form */
    @media (max-width: 992px) {

    /* Adjust breakpoint for filter form */
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

    {{-- Filter Form --}}
    <form action="{{ route('admin.mahasiswas.index') }}" method="GET" class="filter-form">
    <div class="filter-group">
      <label for="mahasiswa_nim">NIM Mahasiswa</label>
      <input type="text" id="mahasiswa_nim" name="mahasiswa_nim" value="{{ request('mahasiswa_nim') }}"
      placeholder="Cari NIM mahasiswa...">
    </div>
    <div class="filter-group">
      <label for="mahasiswa_nama">Nama Mahasiswa</label>
      <input type="text" id="mahasiswa_nama" name="mahasiswa_nama" value="{{ request('mahasiswa_nama') }}"
      placeholder="Cari nama mahasiswa...">
    </div>
    <div class="filter-buttons">
      <button type="submit" class="btn btn-primary">
      <i class="fas fa-filter"></i> <span class="btn-text">Filter</span>
      </button>
      <a href="{{ route('admin.mahasiswas.index') }}" class="btn btn-secondary">
      <i class="fas fa-sync-alt"></i> <span class="btn-text">Reset</span>
      </a>
    </div>
    </form>

    <div class="page-header">
    <h3 class="page-title">Daftar Mahasiswa</h3>
    <a href="{{ route('admin.mahasiswas.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i>
      <span class="btn-text">Tambah Mahasiswa</span>
    </a>
    </div>

    <div class="data-table-container">
    <table class="data-table">
      <thead>
      <tr>
        <th>ID</th>
        <th>NIM</th>
        <th>Nama</th>
        <th>Angkatan</th>
        <th>Tanggal Lahir</th>
        <th>Program Studi</th>
        <th>Prog. Perkuliahan</th>
        <th>Kelas</th>
        <th>Status</th>
        <th>Email</th>
        <th>Telepon</th>
        <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
      @forelse ($mahasiswas as $mhs)
      <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $mhs->nim }}</td>
      <td>{{ $mhs->nama }}</td>
      <td>{{ $mhs->angkatan }}</td>
      <td>{{ $mhs->tanggal_lahir ? \Carbon\Carbon::parse($mhs->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
      <td>{{ $mhs->program_studi ?? '-' }}</td>
      <td>{{ $mhs->prog_perkuliahan ?? '-' }}</td>
      <td>{{ $mhs->kelas ?? '-' }}</td>
      <td>{{ $mhs->status_mahasiswa ?? '-' }}</td>
      <td>{{ $mhs->email }}</td>
      <td>{{ $mhs->telepon ?? '-' }}</td>
      <td>
      <div class="action-buttons">
        <a href="{{ route('admin.mahasiswas.edit', $mhs->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i>
        <span class="btn-text">Edit</span>
        </a>
        <form action="{{ route('admin.mahasiswas.destroy', $mhs->id) }}" method="POST"
        style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"
        onclick="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini?')">
        <i class="fas fa-trash-alt"></i>
        <span class="btn-text">Hapus</span>
        </button>
        </form>
      </div>
      </td>
      </tr>
    @empty
      <tr>
      <td colspan="12" class="empty-state"> {{-- Adjusted colspan --}}
      <i class="fas fa-user-graduate"></i>
      <p>Tidak ada data mahasiswa</p>
      </td>
      </tr>
    @endforelse
      </tbody>
    </table>
    </div>
  </div>
@endsection