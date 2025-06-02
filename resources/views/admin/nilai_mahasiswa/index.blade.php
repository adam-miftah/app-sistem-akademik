@extends('layouts.app')

@section('title', 'Kelola Nilai Mahasiswa - Admin')
@section('header_title', 'Kelola Nilai Mahasiswa')

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
    vertical-align: middle;
    /* Align header text vertically */
    }

    .data-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
    /* Align cell content vertically */
    }

    .data-table tr:last-child td {
    border-bottom: none;
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

    /* Responsive for Tablet */
    @media (max-width: 1200px) {

    /* Adjust column visibility for smaller screens */
    .data-table th:nth-child(1),
    .data-table td:nth-child(1) {
      display: none;
    }

    /* ID */
    .data-table th:nth-child(3),
    .data-table td:nth-child(3) {
      display: none;
    }

    /* NIM */
    .data-table th:nth-child(9),
    .data-table td:nth-child(9) {
      display: none;
    }

    /* Dosen */
    }

    /* Responsive for Mobile */
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
    .data-table th:nth-child(6),
    .data-table td:nth-child(6) {
      display: none;
    }

    /* Nilai Angka */
    .data-table th:nth-child(7),
    .data-table td:nth-child(7) {
      display: none;
    }

    /* Nilai Huruf */
    .data-table th:nth-child(8),
    .data-table td:nth-child(8) {
      display: none;
    }

    /* Mutu */
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
    .filter-group select {
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
  </style>

  <div class="content-area">
    @if (session('success'))
    <div class="alert-success">
    <i class="fas fa-check-circle"></i>
    {{ session('success') }}
    </div>
    @endif

    {{-- Filter Form --}}
    <form action="{{ route('admin.nilaiMahasiswas.index') }}" method="GET" class="filter-form">
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
    {{-- Anda bisa menambahkan filter lain di sini jika diperlukan, contoh: filter semester, mata kuliah, dll. --}}
    <div class="filter-buttons">
      <button type="submit" class="btn btn-primary">
      <i class="fas fa-filter"></i> <span class="btn-text">Filter</span>
      </button>
      <a href="{{ route('admin.nilaiMahasiswas.index') }}" class="btn btn-secondary">
      <i class="fas fa-sync-alt"></i> <span class="btn-text">Reset</span>
      </a>
    </div>
    </form>

    <div class="page-header">
    <h3 class="page-title">Daftar Nilai Mahasiswa</h3>
    <a href="{{ route('admin.nilaiMahasiswas.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i>
      <span class="btn-text">Input Nilai Baru</span>
    </a>
    </div>

    <div class="data-table-container">
    <table class="data-table">
      <thead>
      <tr>
        <th>ID</th>
        <th>Mahasiswa</th>
        <th>NIM</th>
        <th>Mata Kuliah</th>
        <th>Kode MK</th>
        <th>SKS</th>
        <th>Kehadiran</th>
        <th>Tugas</th>
        <th>UTS</th>
        <th>UAS</th>
        <th>Nilai Akhir</th>
        <th>Grade</th>
        <th>Mutu</th>
        {{-- <th>Dosen</th> --}}
        <th>Aksi</th>
      </tr>
      </thead>
      <tbody>
      @php
      $totalSKSAllSemesters = 0;
      $totalSKSxMutuAllSemesters = 0;
      // Grouping by semester is now optional or handled differently if needed by the display logic
      // $groupedBySemester = $nilaiMahasiswas->groupBy('semester');
      @endphp

      {{-- Looping langsung nilaiMahasiswas karena sudah difilter dan diurutkan di controller --}}
      @forelse ($nilaiMahasiswas as $nilai)
      <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $nilai->mahasiswa->nama }}</td>
      <td>{{ $nilai->mahasiswa->nim }}</td>
      <td>{{ $nilai->mataKuliah->nama_mk }}</td>
      <td>{{ $nilai->mataKuliah->kode_mk }}</td>
      <td>{{ $nilai->mataKuliah->sks }}</td>

      <td>{{ $nilai->kehadiran ?? '-' }}</td>
      <td>{{ $nilai->nilai_tugas ?? '-' }}</td>
      <td>{{ $nilai->nilai_uts ?? '-' }}</td>
      <td>{{ $nilai->nilai_uas ?? '-' }}</td>
      <td>{{ $nilai->nilai_angka ?? '-' }}</td>
      <td>{{ $nilai->nilai_huruf ?? '-' }}</td>
      <td>{{ $nilai->mutu ?? '-' }}</td>
      {{-- <td>{{ $nilai->dosen->nama ?? '-' }}</td> --}}
      <td>
      <div class="action-buttons">
        <a href="{{ route('admin.nilaiMahasiswas.edit', $nilai->id) }}" class="btn btn-warning">
        <i class="fas fa-edit"></i>
        <span class="btn-text">Edit</span>
        </a>
        <form action="{{ route('admin.nilaiMahasiswas.destroy', $nilai->id) }}" method="POST"
        style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger"
        onclick="return confirm('Apakah Anda yakin ingin menghapus nilai ini?')">
        <i class="fas fa-trash-alt"></i>
        <span class="btn-text">Hapus</span>
        </button>
        </form>
      </div>
      </td>
      </tr>
    @empty
      <tr>
      <td colspan="16" class="empty-state">
      <i class="fas fa-graduation-cap"></i>
      <p>Tidak ada data nilai mahasiswa</p>
      </td>
      </tr>
    @endforelse
      </tbody>
      <tfoot>
      {{-- The row below has been removed as per your request --}}
      {{--
      <tr class="table-primary">
        <th colspan="5" class="text-end">Total SKS Keseluruhan:</th>
        <th>{{ $totalSKSAllSemesters }}</th>
        <th colspan="6" class="text-end">Indeks Prestasi Kumulatif (IPK):</th>
        <th colspan="4">
        @if($totalSKSAllSemesters > 0)
        {{ number_format($totalSKSxMutuAllSemesters / $totalSKSAllSemesters, 2) }}
        @else
        0.00
        @endif
        </th>
      </tr>
      --}}
      </tfoot>
    </table>
    </div>
  </div>
@endsection