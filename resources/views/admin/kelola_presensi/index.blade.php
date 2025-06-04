{{-- File: resources/views/admin/kelola_presensi/index.blade.php --}}
@extends('layouts.app') {{-- Sesuaikan dengan layout admin Anda --}}

@section('title', 'Kelola Presensi Mahasiswa')
@section('header_title', 'Kelola Presensi Mahasiswa')

@section('content')
  <style>
    .filter-card {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .filter-card h5 {
    margin-bottom: 1rem;
    font-weight: 600;
    }

    .form-select,
    .form-control {
    border-radius: 6px;
    }

    /* Styles for Action Buttons, adapted from your example */
    .btn {
    /* General button styling */
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    /* Adjusted for btn-sm consistency */
    border-radius: 6px;
    /* Consistent with other buttons */
    font-weight: 500;
    text-decoration: none;
    transition: background-color 0.2s ease-in-out, transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
    /* Standard small button size */
    }

    .btn-warning.btn-sm {
    /* Specific for Edit button */
    background-color: #ffc107;
    /* Bootstrap warning yellow */
    color: #212529;
    /* Dark text for yellow background */
    }

    .btn-warning.btn-sm:hover {
    background-color: #e0a800;
    transform: translateY(-1px);
    /* Subtle hover effect */
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-danger.btn-sm {
    /* Specific for Delete button */
    background-color: #dc3545;
    /* Bootstrap danger red */
    color: white;
    }

    .btn-danger.btn-sm:hover {
    background-color: #c82333;
    transform: translateY(-1px);
    /* Subtle hover effect */
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    }

    .table-actions {
    /* Container for buttons in table cell */
    display: flex;
    gap: 0.5rem;
    /* Space between buttons */
    justify-content: center;
    /* Center buttons if cell is text-center */
    }

    .table-actions form {
    display: inline-block;
    /* Keep form inline with other elements if not flexed directly */
    }

    /* End of Action Button Styles */

    .badge-status-hadir {
    background-color: #28a745;
    color: white;
    }

    .badge-status-alpha {
    background-color: #dc3545;
    color: white;
    }

    .badge-status-izin {
    background-color: #ffc107;
    color: #212529;
    }

    .badge-status-sakit {
    background-color: #17a2b8;
    color: white;
    }

    .badge-status-tidak_hadir {
    background-color: #6c757d;
    color: white;
    }


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

    .alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    border-left: 4px solid #dc3545;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    }

    .data-table-container {
    overflow-x: auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 2rem;
    }

    .data-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1000px;
    }

    .data-table thead {
    background-color: #4361ee;
    color: white;
    }

    .data-table th,
    .data-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #f0f0f0;
    }

    .data-table th {
    font-weight: 500;
    }

    .data-table tr:last-child td {
    border-bottom: none;
    }

    .data-table tr:hover {
    background-color: rgba(67, 97, 238, 0.05);
    }

    .empty-state {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
    }

    .empty-state i {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: #adb5bd;
    }

    .empty-state h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    }

    /* Responsive: Hide text on very small screens for action buttons */
    @media (max-width: 576px) {
    .btn-text {
      display: none;
    }

    .btn i {
      margin-right: 0 !important;
      /* Remove margin if only icon is shown */
    }

    .table-actions {
      flex-direction: column;
      /* Stack buttons vertically on very small screens */
    }
    }
  </style>

  <div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <div class="card filter-card">
    <div class="card-body">
      <h5><i class="fas fa-filter me-2"></i>Filter Presensi</h5>
      <form action="{{ route('admin.kelolaPresensi.index') }}" method="GET">
      <div class="row g-3">
        <div class="col-md-3">
        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
          value="{{ $request->tanggal_mulai }}">
        </div>
        <div class="col-md-3">
        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai"
          value="{{ $request->tanggal_selesai }}">
        </div>
        <div class="col-md-3">
        <label for="mata_kuliah_id" class="form-label">Mata Kuliah</label>
        <select class="form-select" id="mata_kuliah_id" name="mata_kuliah_id">
          <option value="">Semua Mata Kuliah</option>
          @foreach($mataKuliahs as $mk)
        <option value="{{ $mk->id }}" {{ $request->mata_kuliah_id == $mk->id ? 'selected' : '' }}>
        {{ $mk->nama_mk }} ({{ $mk->kode_mk }})
        </option>
      @endforeach
        </select>
        </div>
        <div class="col-md-3">
        <label for="kelas" class="form-label">Kelas</label>
        <select class="form-select" id="kelas" name="kelas">
          <option value="">Semua Kelas</option>
          @foreach($kelasOptions as $kelasOption)
        <option value="{{ $kelasOption }}" {{ $request->kelas == $kelasOption ? 'selected' : '' }}>
        {{ $kelasOption }}
        </option>
      @endforeach
        </select>
        </div>
        <div class="col-md-3">
        <label for="mahasiswa_id" class="form-label">Mahasiswa</label>
        <select class="form-select" id="mahasiswa_id" name="mahasiswa_id">
          <option value="">Semua Mahasiswa</option>
          @foreach($mahasiswas as $mahasiswa)
        <option value="{{ $mahasiswa->id }}" {{ $request->mahasiswa_id == $mahasiswa->id ? 'selected' : '' }}>
        {{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})
        </option>
      @endforeach
        </select>
        </div>
        <div class="col-md-3">
        <label for="dosen_id" class="form-label">Dosen</label>
        <select class="form-select" id="dosen_id" name="dosen_id">
          <option value="">Semua Dosen</option>
          @foreach($dosens as $dosen)
        <option value="{{ $dosen->id }}" {{ $request->dosen_id == $dosen->id ? 'selected' : '' }}>
        {{ $dosen->nama }}
        </option>
      @endforeach
        </select>
        </div>
        <div class="col-md-3">
        <label for="status_kehadiran" class="form-label">Status Kehadiran</label>
        <select class="form-select" id="status_kehadiran" name="status_kehadiran">
          <option value="">Semua Status</option>
          <option value="Hadir" {{ $request->status_kehadiran == 'Hadir' ? 'selected' : '' }}>Hadir</option>
          <option value="Tidak Hadir" {{ $request->status_kehadiran == 'Tidak Hadir' ? 'selected' : '' }}>Tidak Hadir
          </option>
        </select>
        </div>
        <div class="col-md-3 align-self-end">
        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
        <div class="col-md-3 align-self-end">
        <a href="{{ route('admin.kelolaPresensi.index') }}" class="btn btn-secondary w-100"><i
          class="fas fa-sync-alt me-1"></i> Reset</a>
        </div>
      </div>
      </form>
    </div>
    </div>

    <div class="data-table-container">
    <table class="data-table">
      <thead>
      <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Mahasiswa</th>
        <th>NIM</th>
        <th>Mata Kuliah</th>
        <th>Kelas</th>
        <th>Dosen</th>
        <th>Status</th>
        <th>Waktu</th>
        <th class="text-center">Aksi</th>
      </tr>
      </thead>
      <tbody>
      @forelse($presensiRecords as $index => $record)
      <tr>
      <td>{{ $presensiRecords->firstItem() + $index }}</td>
      <td>{{ \Carbon\Carbon::parse($record->tanggal)->format('d/m/y') }}</td>
      <td>{{ $record->mahasiswa->nama ?? 'N/A' }}</td>
      <td>{{ $record->mahasiswa->nim ?? 'N/A' }}</td>
      <td>{{ $record->pengampuMataKuliah->mataKuliah->nama_mk ?? 'N/A' }}</td>
      <td>{{ $record->pengampuMataKuliah->kelas ?? 'N/A' }}</td>
      <td>{{ $record->pengampuMataKuliah->dosen->nama ?? 'N/A' }}</td>
      <td>
      @php
      $statusClass = 'badge-status-' . strtolower(str_replace(' ', '_', $record->status_kehadiran));
      @endphp
      <span class="badge {{ $statusClass }}">
        {{ $record->status_kehadiran }}
      </span>
      </td>
      <td>{{ $record->waktu_presensi ? \Carbon\Carbon::parse($record->waktu_presensi)->format('H:i:s') : '-' }}</td>
      <td class="text-center">
      <div class="table-actions">
        <a href="{{ route('admin.kelolaPresensi.edit', $record->id) }}" class="btn btn-sm btn-warning">
        <i class="fas fa-edit"></i>
        <span class="btn-text">Edit</span>
        </a>
        <form action="{{ route('admin.kelolaPresensi.destroy', $record->id) }}" method="POST"
        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
        <i class="fas fa-trash-alt"></i>
        <span class="btn-text">Hapus</span>
        </button>
        </form>
      </div>
      </td>
      </tr>
    @empty
      <tr>
      <td colspan="10" class="text-center">
      <div class="empty-state">
        <i class="fas fa-folder-open"></i>
        <h3 class="mt-3">Tidak ada data presensi</h3>
        <p>Data presensi mahasiswa tidak ditemukan dengan filter yang diterapkan atau belum ada data.</p>
      </div>
      </td>
      </tr>
    @endforelse
      </tbody>
    </table>
    </div>

    @if ($presensiRecords->hasPages())
    <div class="d-flex justify-content-center">
    {{ $presensiRecords->links() }}
    </div>
    @endif

  </div>
@endsection