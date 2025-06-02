@extends('layouts.app')

@section('title', 'Kelola Data Mata Kuliah - Admin')
@section('header_title', 'Kelola Data Mata Kuliah')

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

    .btn-secondary {
    /* Added for Reset button */
    background-color: #6c757d;
    color: white;
    }

    .btn-secondary:hover {
    /* Added for Reset button */
    background-color: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.2);
    }

    .btn-warning {
    color: var(--text-color);
    /* Kepp default color for warning (usually black/dark text on yellow background) */
    }

    .btn-warning:hover {
    transform: translateY(-2px);
    }

    .btn-danger {
    background-color: var(--danger-color);
    /* Ensure --danger-color is defined in your CSS variables */
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
    min-width: 800px;
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
    @media (max-width: 992px) {

    .data-table td:nth-child(6),
    .data-table th:nth-child(6) {
      display: none;
    }
    }

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
    min-width: 180px;
    /* Minimum width for each filter input */
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

    {{-- Filter Form --}}
    <form action="{{ route('admin.mataKuliahs.index') }}" method="GET" class="filter-form">
    <div class="filter-group">
      <label for="kode_mk">Kode Mata Kuliah</label>
      <input type="text" id="kode_mk" name="kode_mk" value="{{ request('kode_mk') }}"
      placeholder="Cari kode mata kuliah...">
    </div>
    <div class="filter-group">
      <label for="nama_mk">Nama Mata Kuliah</label>
      <input type="text" id="nama_mk" name="nama_mk" value="{{ request('nama_mk') }}"
      placeholder="Cari nama mata kuliah...">
    </div>
    <div class="filter-group">
      <label for="sks">SKS</label>
      <input type="text" id="sks" name="sks" value="{{ request('sks') }}" placeholder="Contoh: 3">
    </div>
    <div class="filter-buttons">
      <button type="submit" class="btn btn-primary">
      <i class="fas fa-filter"></i> <span class="btn-text">Filter</span>
      </button>
      <a href="{{ route('admin.mataKuliahs.index') }}" class="btn btn-secondary">
      <i class="fas fa-sync-alt"></i> <span class="btn-text">Reset</span>
      </a>
    </div>
    </form>

    <div class="page-header">
    <h3 class="page-title">Daftar Mata Kuliah</h3>
    <a href="{{ route('admin.mataKuliahs.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i>
      <span class="btn-text">Tambah Mata Kuliah</span>
    </a>
    </div>

    <div class="data-table-container">
    <table class="data-table">
      <thead>
      <tr>
        <th>ID</th>
        <th>Kode MK</th>
        <th>Nama Mata Kuliah</th>
        <th>SKS</th>
        <th>Kelas</th>
        {{-- <th>Deskripsi</th> --}}
        <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
      @forelse ($mataKuliahs as $mk)
      <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $mk->kode_mk }}</td>
      <td>{{ $mk->nama_mk }}</td>
      <td>{{ $mk->sks }}</td>
      <td>{{ $mk->kelas }}</td> {{-- DATA BERUBAH --}}
      {{-- <td>{{ Str::limit($mk->deskripsi ?? '-', 30) }}</td> --}}
      <td>
      <div class="action-buttons">
        <a href="{{ route('admin.mataKuliahs.edit', $mk->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i>
        <span class="btn-text">Edit</span>
        </a>
        <form action="{{ route('admin.mataKuliahs.destroy', $mk->id) }}" method="POST"
        style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"
        onclick="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')">
        <i class="fas fa-trash-alt"></i>
        <span class="btn-text">Hapus</span>
        </button>
        </form>
      </div>
      </td>
      </tr>
    @empty
      <tr>
      <td colspan="7" class="empty-state">
      <i class="fas fa-book"></i>
      <p>Tidak ada data mata kuliah.</p>
      </td>
      </tr>
    @endforelse
      </tbody>
    </table>
    </div>
  </div>
@endsection