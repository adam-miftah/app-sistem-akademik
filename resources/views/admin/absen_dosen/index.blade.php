@extends('layouts.app')

@section('title', 'Kelola Absen Dosen - Admin')
@section('header_title', 'Kelola Absen Dosen')

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
    background-color: var(--warning-color);
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

    /* Pagination Styling */
    .pagination {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
    padding: 0;
    list-style: none;
    }

    .pagination li {
    margin: 0 0.25rem;
    }

    .pagination li a,
    .pagination li span {
    display: block;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    text-decoration: none;
    color: var(--primary-color);
    transition: all 0.2s ease-in-out;
    }

    .pagination li a:hover {
    background-color: var(--primary-color);
    color: white;
    }

    .pagination li.active span {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    }

    .pagination li.disabled span {
    color: var(--text-light);
    cursor: not-allowed;
    background-color: #f8f9fa;
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
    }

    .filter-group {
    flex: 1;
    min-width: 180px;
    }

    .filter-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-color);
    }

    .filter-group input[type="text"],
    .filter-group input[type="date"] {
    /* Added type date for filter date */
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.875rem;
    color: var(--text-color);
    background-color: var(--white-bg);
    box-sizing: border-box;
    }

    .filter-buttons {
    display: flex;
    gap: 0.75rem;
    }

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

    /* Custom styles for Status column */
    .status-badge {
    display: inline-block;
    padding: 0.3em 0.6em;
    border-radius: 0.3rem;
    font-weight: 600;
    text-align: center;
    white-space: nowrap;
    }

    .status-hadir {
    background-color: #d4edda;
    /* Light green */
    color: #155724;
    /* Dark green */
    }

    .status-tidak-hadir,
    .status-izin,
    .status-sakit,
    .status-alpha {
    /* Combine all non-hadir statuses */
    background-color: #f8d7da;
    /* Light red */
    color: #721c24;
    /* Dark red */
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
    <form action="{{ route('admin.absenDosens.index') }}" method="GET" class="filter-form">
    <div class="filter-group">
      <label for="dosen_nama">Nama Dosen</label>
      <input type="text" id="dosen_nama" name="dosen_nama" value="{{ request('dosen_nama') }}"
      placeholder="Cari nama dosen...">
    </div>
    <div class="filter-buttons">
      <button type="submit" class="btn btn-primary">
      <i class="fas fa-filter"></i> <span class="btn-text">Filter</span>
      </button>
      <a href="{{ route('admin.absenDosens.index') }}" class="btn btn-secondary">
      <i class="fas fa-sync-alt"></i> <span class="btn-text">Reset</span>
      </a>
    </div>
    </form>

    <div class="page-header">
    <h3 class="page-title">Daftar Absen Dosen</h3>
    <a href="{{ route('admin.absenDosens.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i>
      <span class="btn-text">Tambah Absen Dosen Baru</span>
    </a>
    </div>

    <div class="data-table-container">
    <table class="data-table">
      <thead>
      <tr>
        <th>No</th>
        <th widht="20%">Dosen</th>
        <th>Tanggal</th>
        <th>Waktu Masuk</th>
        <th>Waktu Keluar</th>
        <th>Status</th>
        {{-- <th>Keterangan</th> --}}
        <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
      @forelse ($absenDosens as $absenDosen)
      <tr>
        <td>{{ $loop->iteration + ($absenDosens->currentPage() - 1) * $absenDosens->perPage() }}</td>
        <td>{{ $absenDosen->dosen->nama ?? 'N/A' }}</td>
        <td>{{ \Carbon\Carbon::parse($absenDosen->tanggal)->format('d/m/Y') }}</td>
        <td>{{ $absenDosen->waktu_masuk ? \Carbon\Carbon::parse($absenDosen->waktu_masuk)->format('H:i') : '-' }}</td>
        <td>{{ $absenDosen->waktu_keluar ? \Carbon\Carbon::parse($absenDosen->waktu_keluar)->format('H:i') : '-' }}
        </td>
        <td>
        {{-- Apply conditional styling for status --}}
        <span class="status-badge
      @if($absenDosen->status == 'Hadir') status-hadir
      @else status-tidak-hadir
      @endif">
        {{ $absenDosen->status }}
        </span>
        </td>
        {{-- <td>{{ $absenDosen->keterangan ?? '-' }}</td> --}}
        <td>
        <div class="action-buttons">
        <a href="{{ route('admin.absenDosens.edit', $absenDosen->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i>
        <span class="btn-text">Edit</span>
        </a>
        <form action="{{ route('admin.absenDosens.destroy', $absenDosen->id) }}" method="POST"
        style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"
          onclick="return confirm('Apakah Anda yakin ingin menghapus data absen ini?')">
          <i class="fas fa-trash-alt"></i>
          <span class="btn-text">Hapus</span>
        </button>
        </form>
        </div>
        </td>
      </tr>
    @empty
      <tr>
      <td colspan="8" class="empty-state">
      <i class="fas fa-calendar-times"></i>
      <p>Tidak ada data absen dosen.</p>
      </td>
      </tr>
    @endforelse
      </tbody>
    </table>
    </div>

    {{ $absenDosens->links('pagination::bootstrap-5') }}
  </div>
@endsection